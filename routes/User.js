const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const saltRounds = 10;

const db = require('../config/db');

router.post('/register', async (req, res) => {
    const username = req.body.username;
    const hash = await bcrypt.hash(req.body.password, saltRounds);
    const email = req.body.email;

    const checkUsernameQuery = "SELECT * FROM Users WHERE username = ? OR email = ?;";

    db.query(checkUsernameQuery, [username, email], (error, result) => {
        if (error) {
            console.log(error);
            res.status(500).json({
                message: "There was an error with database connection",
                error: error
            });
        } else if (result.length > 0) {
            res.status(401).json({
                message: "Username/e-mail already exists"
            })
        } else {
            const registerQuery = "INSERT INTO `users` (username, password, email) VALUES (?, ?, ?, ?, ?);";
    

            db.query(registerQuery, [username, hash, email], (error, result) => {
                if (error) {
                    console.log(error);
                    res.status(500).json({
                        message: "There was an error with database connection"
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
    const _username = req.body.username;
    const _password = req.body.password;

    const loginQuery = "SELECT * FROM Users WHERE username = ?;";

    db.query(loginQuery, _username, (error, result) => {
        if (error) {
            console.log(error)
            res.status(500).json({
                loggedIn: false,
                username: _username
            })
        }
        if (result.length > 0) {
            
            const { id, username, password } = result[0];

            if (bcrypt.compare(_password, password)) {
                res.status(200).json({ 
                    id: id, 
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

router.get('/id/:id', (req, res) => {
    const id = req.params.id;

    const getUserQuery = "SELECT * FROM Users WHERE id = ?;";

    db.query(getUserQuery, [id], (error, result) => {
        if (error) {
            console.error(error);
            res.status(500).json({
                message: "There was an error with database, please try again in a few seconds..."
            })
        }

        if (result.length == 0) {
            res.status(404).json({
                message: "User not found."
            })
        }

        console.log(result);
        
        const { id, username, email } = result[0];

        res.status(200).json({
            id: id,
            username: username,
            email: email
        })
    })
})

module.exports = router;