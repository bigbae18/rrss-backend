const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const saltRounds = 10;

const db = require('../config/db');

router.post('/register', async (req, res) => {
    const username = req.body.username;
    const hash = await bcrypt.hash(req.body.password, saltRounds);
    const email = req.body.email;
    const time = new Date().toString();

    const checkUsernameQuery = "SELECT * FROM Users WHERE username = ? OR email = ?;";

    db.query(checkUsernameQuery, [username, email], (error, result) => {
        if (error) {
            console.log(error);
            res.status(500).json({
                message: "There were an error with database connection",
                error: error
            });
        } else if (result.length > 0) {
            res.status(401).json({
                message: "Username/e-mail already exists"
            })
        } else {
            const registerQuery = "INSERT INTO `users` (username, password, email, created_at, updated_at) VALUES (?, ?, ?, ?, ?);";
    

            db.query(registerQuery, [username, hash, email, time, time], (error, result) => {
                if (error) {
                    console.log(error);
                    res.status(500).json({
                        message: "There were an error with database connection"
                    });
                }
                res.status(200).json({
                    message: "Registered successfully",
                    username: username
                });
            })
        }
    })
    

    
    
})

router.post('/login', (req, res) => {
    const username = req.body.username;
    const password = req.body.password;

    const loginQuery = "SELECT * FROM Users WHERE username = ?;";

    db.query(loginQuery, username, (error, result) => {
        if (error) {
            console.log(error)
            res.status(500).json({
                loggedIn: false,
                username: username
            })
        }
        if (result.length > 0) {
            if (bcrypt.compare(password, result[0].password)) {
                res.status(200).json({ 
                    loggedIn: true, 
                    username: username
                })
            } else {
                res.status(401).json({ 
                    loggedIn: false, 
                    message: "User/password does not match!"
                })
            }
        } else {
            res.status(400).json({ 
                loggedIn: false, 
                message: "User does not exist!"
            })
        }
    }) 
})

module.exports = router;