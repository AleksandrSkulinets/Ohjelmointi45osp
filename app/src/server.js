const express = require('express');
const cors = require('cors');
const mysql = require('mysql');

const app = express();
const port = 3001;

app.use(cors()); // Apply cors dont know why it does not works without

// MySQL connection
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'verkkokauppa'
});

// Connect to MySQL
connection.connect(err => {
  if (err) {
    console.error('Error connecting to MySQL: ', err);
    return;
  }
  console.log('Connected to MySQL');
});

// API endpoint defenition
app.get('/api/data', (req, res) => {
  const sql = 'SELECT * FROM products';
  connection.query(sql, (err, results) => {
    if (err) {
      console.error('Error fetching data from MySQL: ', err);
      res.status(500).json({ error: 'Error fetching data' });
      return;
    }
    res.json(results);
  });
});

app.listen(port, () => {
  console.log(`Server running on port ${port}`);
});
