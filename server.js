const express = require('express');
const axios = require('axios');
const cheerio = require('cheerio');

const app = express();
const port = process.env.PORT || 3000;

app.use(express.static('public'));

app.get('/getUserData', async (req, res) => {
    const username = req.query.username;
    if (!username) {
        return res.status(400).json({ error: 'Не указан ник' });
    }
    try {
        const response = await axios.get(`https://t.me/${username}`);
        const html = response.data;
        const $ = cheerio.load(html);
        const title = $('meta[property="og:title"]').attr('content');
        const image = $('meta[property="og:image"]').attr('content');
        if (!title || !image) {
            return res.status(404).json({ error: 'Данные не найдены' });
        }
        res.json({ name: title, photo: image });
    } catch (error) {
        res.status(500).json({ error: 'Ошибка при получении данных' });
    }
});

app.listen(port, () => console.log(`http://localhost:${port}`));
