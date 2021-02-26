const express = require('express');
const router = express.Router();

const db = require('../config/db');

router.get('/', (req, res) => {
    const getPostsQuery = "SELECT * FROM Posts ORDER BY `created_at` DESC";

    db.query(getPostsQuery, (error, results) => {
        if (error) {
            console.error(error)
            res.status(500).json({
                message: "There was an error with database connection"
            })
        }
        res.status(200).send(results);
    })
})

router.post('/', (req, res) => {
    const author = req.body.author;
    const body = req.body.body;

    const createPostQuery = "INSERT INTO Posts (author, body) VALUES (?, ?)";

    db.query(createPostQuery, [author, body], (error, result) => {
        if (error) {
            console.error(error);
            res.status(500).json({
                message: "There was an error creating your post"
            })
        }
        res.status(200).json({
            message: "Post created successful"
        })
    })
})

module.exports = router;