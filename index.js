const express = require('express');
const app = express();
const cors = require('cors')

const userRouter = require('./routes/User')

app.use(cors());
app.use(express.json());
app.use('/user', userRouter);

app.listen(3001, (req, res) => {
    console.log('Server running, ES6 babel preset and CORS enabled.')
})