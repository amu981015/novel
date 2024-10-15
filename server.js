const express = require('express');
const sql = require('mssql');
const multer = require('multer');
const fs = require('fs');
const cors = require('cors');
const path = require('path');

const app = express();
app.use(cors());
app.use(express.json());

process.env.NODE_ENV = 'utf8';

// 設置靜態文件服務，使上傳的文件可以通過 URL 訪問
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

// 配置文件上傳的存儲方式和文件名生成規則
const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    cb(null, 'uploads/')
  },
  filename: function (req, file, cb) {
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9)
    cb(null, file.fieldname + '-' + uniqueSuffix + path.extname(file.originalname))
  }
})

const upload = multer({
  storage: storage,
  fileFilter: (req, file, cb) => {
    if (file.fieldname === 'coverImage' || file.fieldname.startsWith('chapterFile')) {
      cb(null, true);
    } else {
      cb(new Error('Unexpected field'));
    }
  }
});

// 數據庫連接配置
const config = {
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  server: process.env.DB_HOST,
  database: 'Novel',
  options: {
    encrypt: true,
    trustServerCertificate: true,
    enableArithAbort: true,
  },
};


// 連接到數據庫
sql.connect(config).then(() => {
  console.log('已連接到數據庫');
}).catch((err) => {
  console.error('數據庫連接失敗：', err);
});

// API 路由：獲取所有小說列表
app.get('/api/novels', async (req, res) => {
  try {
    console.log('開始獲取小說列表');
    
    // 首先獲取總記錄數
    const countResult = await sql.query`SELECT COUNT(*) as total FROM Novels`;
    const totalNovels = countResult.recordset[0].total;
    console.log(`數據庫中總共有 ${totalNovels} 本小說`);

    // 然後獲取所有小說
    const result = await sql.query`
      SELECT NovelId, title, author, intro, coverImage, createdAt, updatedAt 
      FROM Novels 
      ORDER BY updatedAt DESC
    `;
    
    console.log(`查詢返回了 ${result.recordset.length} 本小說`);
    console.log('從數據庫獲取的小說：', JSON.stringify(result.recordset, null, 2));

    if (result.recordset.length !== totalNovels) {
      console.warn('警告：返回的小說數量與數據庫中的總數不符');
    }

    res.json(result.recordset);
  } catch (err) {
    console.error('獲取小說列表失敗：', err);
    res.status(500).json({ error: err.message });
  }
});

// API 路由：加新小說
app.post('/api/novels', upload.any(), async (req, res) => {
  try {
    console.log('開始處理新小說請求');
    console.log('請求體:', req.body);
    console.log('上傳的文件:', req.files);

    const { title, author, intro } = req.body;
    let coverImagePath = null;

    const coverImageFile = req.files.find(file => file.fieldname === 'coverImage');
    if (coverImageFile) {
      coverImagePath = coverImageFile.path;
      console.log('封面圖片路徑:', coverImagePath);
    }

    console.log('準備插入小說數據');
    const result = await sql.query`
      INSERT INTO Novels (title, author, intro, coverImage, createdAt, updatedAt) 
      OUTPUT INSERTED.NovelId 
      VALUES (${title}, ${author}, ${intro}, ${coverImagePath}, GETDATE(), GETDATE())
    `;
    const novelId = result.recordset[0].NovelId;
    console.log('小說插入成功,ID:', novelId);

    const chapterFiles = req.files.filter(file => file.fieldname === 'chapterFiles');
    console.log('章節文件數量:', chapterFiles.length);

    for (const file of chapterFiles) {
      // 使用 Buffer 來正確處理 UTF-8 編碼的文件名
      const chapterTitle = Buffer.from(file.originalname, 'latin1').toString('utf8');
      const chapterContent = fs.readFileSync(file.path, 'utf-8');

      console.log('Chapter title before insertion:', chapterTitle); // 添加日誌

      await sql.query`
        INSERT INTO Chapters (novelId, chapterNumber, title, content, createdAt, updatedAt) 
        VALUES (${novelId}, ${chapterFiles.indexOf(file) + 1}, ${chapterTitle}, ${chapterContent}, GETDATE(), GETDATE())
      `;
    }

    res.json({ message: '小說及章節添加成功' });
  } catch (error) {
    console.error('添加小說失敗：', error);
    res.status(500).json({ error: error.message });
  }
});

// API 路由：獲取特定小說的詳情
app.get('/api/novels/:NovelId', async (req, res) => {
  try {
    const result = await sql.query`SELECT * FROM Novels WHERE NovelId = ${req.params.NovelId}`;
    if (result.recordset.length === 0) {
      res.status(404).json({ error: '小說未找到' });
    } else {
      res.json(result.recordset[0]);
    }
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// API 路由：獲取特定小說的所有章節
app.get('/api/novels/:NovelId/chapters', async (req, res) => {
  try {
    const result = await sql.query`
      SELECT ChapterID, ChapterNumber, Title, CreatedAt, UpdatedAt 
      FROM Chapters 
      WHERE NovelId = ${req.params.NovelId} 
      ORDER BY ChapterNumber
    `;
    res.json(result.recordset);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// API 路由：獲取特定章節的內容
app.get('/api/novels/:NovelId/chapters/:ChapterID', async (req, res) => {
  try {
    const result = await sql.query`SELECT * FROM Chapters WHERE ChapterID = ${req.params.ChapterID} AND NovelId = ${req.params.NovelId}`;
    if (result.recordset.length === 0) {
      res.status(404).json({ error: '章節未找到' });
    } else {
      const chapter = result.recordset[0];
      
      // 獲取上一章和下一章的 ChapterID
      const prevChapter = await sql.query`SELECT TOP 1 ChapterID FROM Chapters WHERE NovelId = ${req.params.NovelId} AND ChapterNumber < ${chapter.ChapterNumber} ORDER BY ChapterNumber DESC`;
      const nextChapter = await sql.query`SELECT TOP 1 ChapterID FROM Chapters WHERE NovelId = ${req.params.NovelId} AND ChapterNumber > ${chapter.ChapterNumber} ORDER BY ChapterNumber ASC`;
      
      chapter.prevChapterId = prevChapter.recordset[0]?.ChapterID;
      chapter.nextChapterId = nextChapter.recordset[0]?.ChapterID;
      
      res.json(chapter);
    }
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// API 路由：刪除特定小說及其所有章節
app.delete('/api/novels/:NovelId', async (req, res) => {
  try {
    await sql.query`DELETE FROM Chapters WHERE NovelId = ${req.params.NovelId}`;
    await sql.query`DELETE FROM Novels WHERE NovelId = ${req.params.NovelId}`;
    res.status(204).end();
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

function parseChapters(content) {
  // 定義中文數字到阿拉伯數字的映射
  const chineseNumbers = {
    '零': 0, '一': 1, '二': 2, '三': 3, '四': 4, '五': 5, '六': 6, '七': 7, '八': 8, '九': 9,
    '十': 10, '百': 100, '千': 1000, '萬': 10000
  };

  // 將中文數字轉換為阿拉伯數字
  function chineseToArabic(chineseNum) {
    let result = 0;
    let temp = 0;
    let prevUnit = 1;
    for (let char of chineseNum) {
      if (chineseNumbers[char] < 10) {
        temp = chineseNumbers[char];
      } else {
        if (temp === 0) temp = 1;
        if (chineseNumbers[char] > prevUnit) {
          result += temp * chineseNumbers[char];
          temp = 0;
        } else {
          result += temp;
          temp = 0;
        }
        prevUnit = chineseNumbers[char];
      }
    }
    result += temp;
    return result;
  }

  // 使用只匹配中文數字的正則表達式來匹配章節
  const chapterRegex = /第([零一二三四五六七八九十百千萬]+)章\s*[:：]?\s*(.*?)(?:\n|\r\n)([\s\S]*?)(?=第[零一二三四五六七八九十百千萬]+章|$)/g;
  const chapters = [];
  let match;
  let chapterNumber = 1;

  while ((match = chapterRegex.exec(content)) !== null) {
    const chapterNum = chineseToArabic(match[1]);
    chapters.push({
      number: chapterNum || chapterNumber,
      title: match[2].trim(),
      content: match[3].trim()
    });
    chapterNumber++;
  }

  // 如果沒有找到任何章節，將整個內容作為一個章節
  if (chapters.length === 0) {
    chapters.push({
      number: 1,
      title: '第一章',
      content: content.trim()
    });
  }

  return chapters;
}

// 獲取特定小說的信息
app.get('/api/novels/:id', async (req, res) => {
  try {
    const result = await sql.query`SELECT * FROM Novels WHERE NovelId = ${req.params.id}`;
    console.log('Novel query result:', result.recordset);
    if (result.recordset.length > 0) {
      res.json(result.recordset[0]);
    } else {
      res.status(404).json({ message: '找不到該小說' });
    }
  } catch (err) {
    console.error('獲取小說信息失敗：', err);
    res.status(500).json({ error: err.message });
  }
});

// 獲取特定小說的所有章節
app.get('/api/novels/:id/chapters', async (req, res) => {
  try {
    const result = await sql.query`
      SELECT ChapterID, chapterNumber, title
      FROM Chapters 
      WHERE novelId = ${req.params.id} 
      ORDER BY chapterNumber
    `;
    
    console.log('Chapters query result:', result.recordset); // 保留這個日誌

    // 確保標題正確編碼
    const chapters = result.recordset.map(chapter => ({
      ...chapter,
      Title: encodeURIComponent(chapter.title) // 使用 encodeURIComponent 編碼標題
    }));

    console.log('Processed chapters:', chapters); // 保留這個日誌

    res.json(chapters);
  } catch (err) {
    console.error('獲取章節列表失敗：', err);
    res.status(500).json({ error: err.message });
  }
});

// 刪除小說及其所有章節
app.delete('/api/novels/:id', async (req, res) => {
  try {
    // 開始事務
    const transaction = new sql.Transaction();
    await transaction.begin();

    try {
      // 首先刪除所有相關的章節
      await transaction.request()
        .input('novelId', sql.Int, req.params.id)
        .query('DELETE FROM Chapters WHERE novelId = @novelId');

      // 然後刪除小說
      const result = await transaction.request()
        .input('novelId', sql.Int, req.params.id)
        .query('DELETE FROM Novels WHERE NovelId = @novelId');

      // 提交事務
      await transaction.commit();

      if (result.rowsAffected[0] > 0) {
        res.json({ message: '小說及其章節已成功刪除' });
      } else {
        res.status(404).json({ message: '找不到該小說' });
      }
    } catch (err) {
      // 如果出錯，回滾事務
      await transaction.rollback();
      throw err;
    }
  } catch (err) {
    console.error('刪除小說失敗：', err);
    res.status(500).json({ error: err.message });
  }
});

const PORT = process.env.PORT || 8080;
app.listen(PORT, () => {
  console.log(`服務器運行在端口 ${PORT}`);
});