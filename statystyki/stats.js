const Database = require('better-sqlite3')

class RestaurantStatsData {
  orderNotSupportedQuery = "SELECT 'SPECIFIED ORDER VALUE IS WRONG'"
  isOrderSupported = function (order) {
    if (['ASC', 'DESC'].includes(order))
      return true
    return false
  }

  mealsEarningsSQL = function (order, number_of_rows) {
    if (this.isOrderSupported(order))
      return `SELECT meal_name, SUM(meal_price) earned FROM meals GROUP BY meal_name ORDER BY earned ${order} LIMIT ${number_of_rows};`
    else 
      return this.orderNotSupportedQuery
  }

  categoriesEarningsSQL = function (order, number_of_rows) {
    if (this.isOrderSupported(order))
      return `SELECT category_name, SUM(meal_price) earned FROM meals GROUP BY meal_name ORDER BY earned ${order} LIMIT ${number_of_rows};`
    else 
      return this.orderNotSupportedQuery
  }

  constructor(dbPath, amountOfData) {
    this.db = new Database(dbPath, { verbose: console.log })
    this.amountOfData = amountOfData
  }

  getQueryResults(query) {
    const stmt = this.db.prepare(query)
    const rows = stmt.all()
    return rows
  }

  get topEarnerMeals(){
    return this.getQueryResults(this.mealsEarningsSQL("DESC", this.amountOfData))
  }
  get worstEarnerMeals(){
    return this.getQueryResults(this.mealsEarningsSQL("ASC", this.amountOfData))
  }
}

const stats = new RestaurantStatsData('stats.db', 3)
console.log("Top earning meals")
console.log(stats.topEarnerMeals)
console.log("Worst earning meals")
console.log(stats.worstEarnerMeals)





