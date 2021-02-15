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
        }
        if (result.length > 0) {
            if (bcrypt.compare(password, results[0].password)) {
                res.json({ 
                    loggedIn: true, 
                    username: username,
                    code: 200
                })
            } else {
                res.json({ 
                    loggedIn: false, 
                    message: "User/password does not match!",
                    code: 401
                })
            }
        } else {
            res.json({ 
                loggedIn: false, 
                message: "User does not exist!",
                code: 401
            })
        }
    }) 
})

module.exports = router;