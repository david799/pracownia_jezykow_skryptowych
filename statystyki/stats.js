const sqlite3 = require('sqlite3').verbose();

let db = new sqlite3.Database('stats.db');

function topEarnerMeals(){
  let sql = `SELECT meal_name, SUM(meal_price) earned FROM meals GROUP BY meal_name ORDER BY earned DESC;`;
  let topEarners = []
  
  db.all(sql, [], (err, rows) => {
    if (err) {
      throw err;
    }
    console.log("Top earner meals")
    console.log(rows)
  });
  return topEarners;
}

function worstEarnerMeals(){
  let sql = `SELECT meal_name, SUM(meal_price) earned FROM meals GROUP BY meal_name ORDER BY earned ASC;`;
  let topEarners = []
  
  db.all(sql, [], (err, rows) => {
    if (err) {
      throw err;
    }
    console.log("Worst earner meals")
    console.log(rows)
  });
  return topEarners;
}

topEarnerMeals()
worstEarnerMeals()

db.close();
