const express = require('express');
const router = express.Router();

const db = require('../config/db');

router.get('/likes/:id', (req, res) => {
    const { id } = req.params;

    const getLikesQuery = "SELECT * FROM likes WHERE post_id = ?;"

    db.query(getLikesQuery, [id], (error, results) => {
        if (error) {
            console.error(error)
            res.status(500).json({
                message: "There was an error with database connection"
            })
        }
        res.status(200).send(results);
    })
});

router.get('/dislikes/:id', (req, res) => {
    const { id } = req.params;

    const getDislikesQuery = "SELECT * FROM dislikes WHERE post_id = ?;";

    db.query(getDislikesQuery, [id], (error, results) => {
        if (error) {
            console.error(error)
            res.status(500).json({
                message: "There was an error with database connection"
            })
        }
        res.status(200).send(results);
    })

});

router.post('/like/add', (req, res) => {
    const { postId, userId } = req.body;

    const addLikeQuery = "INSERT INTO likes (post_id, user_id) VALUES (?, ?);";

    db.query(addLikeQuery, [postId, userId], (error, result) => {
        if (error) {
            console.error(error)
            res.status(500).json({
                message: "There was an error with database connection"
            })
        }
        console.log(result)

        const updateLikeCountQuery = "UPDATE posts SET likes = likes + 1 WHERE id = ?";
        db.query(updateLikeCountQuery, [postId], (errors, results) => {
            if (errors) {
                console.error(errors)
                res.status(500).json({
                    message: "There was an error with database connection"
                })
            }
            res.status(200).json({
                message: "Liked succesfully"
            })
        })
        
    })
});
router.post('/like/remove', (req, res) => {
    const { postId, userId } = req.body;

    const removeLikeQuery = "DELETE FROM likes WHERE post_id = ? AND user_id = ?;";

    db.query(removeLikeQuery, [postId, userId], (error, result) => {
        if (error) {
            console.error(error)
            res.status(500).json({
                message: "There was an error with database connection"
            })
        }

        const updateLikeCountQuery = "UPDATE posts SET likes = likes - 1 WHERE id = ?";
        db.query(updateLikeCountQuery, [postId], (errors, results) => {
            if (errors) {
                console.error(errors)
                res.status(500).json({
                    message: "There was an error with database connection"
                })
            }
            res.status(200).json({
                message: "Liked succesfully"
            })
        })
    })
});

router.post('/dislike/add', (req, res) => {
    const { postId, userId } = req.body;

    const addDislikeQuery = "INSERT INTO dislikes (post_id, user_id) VALUES (?, ?);";

    db.query(addDislikeQuery, [postId, userId], (error, result) => {
        if (error) {
            console.error(error)
            res.status(500).json({
                message: "There was an error with database connection"
            })
        }
        const updateDislikeCountQuery = "UPDATE posts SET unlikes = unlikes + 1 WHERE id = ?;";

        db.query(updateDislikeCountQuery, [postId], (errors, results) => {

            if (errors) {
                console.error(errors);
                res.status(500).json({
                    message: "There was an error with database connection"
                })
            }

            res.status(200).json({
                message: "Disliked succesfuly"
            })
        })
    })
});
router.post('/dislike/remove', (req, res) => {
    const { postId, userId } = req.body;

    const removeDislikeQuery = "DELETE FROM dislikes WHERE post_id = ? AND user_id = ?;";

    db.query(removeDislikeQuery, [postId, userId], (errors, results) => {
        if (errors) {
            console.error(errors);
            res.status(500).json({
                message: "There was an error with database connection"
            })
        }
        const updateDislikeCountQuery = "UPDATE posts SET unlikes = unlikes -1 WHERE id = ?;";
        db.query(updateDislikeCountQuery, [postId], (error, result) => {
            if (error) {
                console.error(error);
                res.status(500).json({
                    message: "There was an error with database connection"
                })
            }
            res.status(200).json({
                message: "Dislike removed succesfuly"
            })
        })
    })
});

module.exports = router;