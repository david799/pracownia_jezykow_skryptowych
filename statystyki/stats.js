const Database = require('better-sqlite3')

class RestaurantStatsData {
  orderNotSupportedQuery = "SELECT 'SPECIFIED ORDER VALUE IS WRONG'"

  constructor(dbPath, amountOfData) {
    this.db = new Database(dbPath, { verbose: console.log })
    this.amountOfData = amountOfData
  }

  getQueryResults(query) {
    const stmt = this.db.prepare(query)
    const rows = stmt.all()
    return rows
  }

  isOrderSupported = function (order) {
    if (['ASC', 'DESC'].includes(order))
      return true
    return false
  }

  earningsByDaySQL = function (order, number_of_rows) {
    return `SELECT order_date, SUM(to_pay) earned FROM orders GROUP BY order_date ORDER BY order_date ${order} LIMIT ${number_of_rows};`
  }

  ordersAmountByDaySQL = function (order, number_of_rows) {
    return `SELECT order_date, COUNT(order_date) quantity FROM orders GROUP BY order_date ORDER BY order_date ${order} LIMIT ${number_of_rows};`
  }

  mealsEarningsSQL = function (order, number_of_rows) {
    if (this.isOrderSupported(order))
      return `SELECT meal_name, SUM(meal_price) earned FROM meals GROUP BY meal_name ORDER BY earned ${order} LIMIT ${number_of_rows};`
    else 
      return this.orderNotSupportedQuery
  }

  categoriesEarningsSQL = function (order, number_of_rows) {
    if (this.isOrderSupported(order))
      return `SELECT category_name, SUM(meal_price) earned FROM meals GROUP BY category_name ORDER BY earned ${order} LIMIT ${number_of_rows};`
    else 
      return this.orderNotSupportedQuery
  }

  mealsAmountSQL = function (order, number_of_rows) {
    if (this.isOrderSupported(order))
      return `SELECT meal_name, COUNT(meal_name) quantity FROM meals GROUP BY meal_name ORDER BY quantity ${order} LIMIT ${number_of_rows};`
    else 
      return this.orderNotSupportedQuery
  }

  categoriesAmountSQL = function (order, number_of_rows) {
    if (this.isOrderSupported(order))
      return `SELECT category_name, COUNT(category_name) quantity FROM meals GROUP BY category_name ORDER BY quantity ${order} LIMIT ${number_of_rows};`
    else 
      return this.orderNotSupportedQuery
  }

  earningsInDeliveryPlaceSQL = function (order, number_of_rows) {
    if (this.isOrderSupported(order))
      return `SELECT delivery_place, SUM(to_pay) earned FROM orders GROUP BY delivery_place ORDER BY earned ${order} LIMIT ${number_of_rows};`
    else 
      return this.orderNotSupportedQuery
  }

  ordersAmountsInDeliveryPlaceSQL = function (order, number_of_rows) {
    if (this.isOrderSupported(order))
      return `SELECT delivery_place, COUNT(delivery_place) quantity FROM orders GROUP BY delivery_place ORDER BY quantity ${order} LIMIT ${number_of_rows};`
    else 
      return this.orderNotSupportedQuery
  }

  earningsUsingPromoCodeSQL = function (order, number_of_rows) {
    if (this.isOrderSupported(order))
      return `SELECT discount_code, SUM(to_pay) earned FROM orders GROUP BY discount_code ORDER BY earned ${order} LIMIT ${number_of_rows};`
    else 
      return this.orderNotSupportedQuery
  }

  ordersAmountsUsingPromoCodeSQL = function (order, number_of_rows) {
    if (this.isOrderSupported(order))
      return `SELECT discount_code, COUNT(discount_code) quantity FROM orders GROUP BY discount_code ORDER BY quantity ${order} LIMIT ${number_of_rows};`
    else 
      return this.orderNotSupportedQuery
  }

  get earningsByDays(){
    return this.getQueryResults(this.earningsByDaySQL("DESC", this.amountOfData))
  }
  get ordersAmountByDays(){
    return this.getQueryResults(this.ordersAmountByDaySQL("DESC", this.amountOfData))
  }
  get earningsByMonths(){
    let months = {}
    let rows = this.earningsByDays
    for(let i=0; i<rows.length; i++)
    {
      let month = rows[i].order_date.substring(0, rows[i].order_date.length-3)
      if (months.hasOwnProperty(month)) {
        months[month] += rows[i].earned
      }
      else{
        months[month] = rows[i].earned
      }
    }
    
    return months
  }
  get ordersAmountsByMonths(){
    let months = {}
    let rows = this.ordersAmountByDays
    for(let i=0; i<rows.length; i++)
    {
      let month = rows[i].order_date.substring(0, rows[i].order_date.length-3)
      if (months.hasOwnProperty(month)) {
        months[month] += rows[i].quantity
      }
      else{
        months[month] = rows[i].quantity
      }
    }
    
    return months
  }
  get ordersAmountByMonths(){
    return this.getQueryResults(this.ordersAmountByDaySQL("DESC", this.amountOfData))
  }
  get topEarnerMeals(){
    return this.getQueryResults(this.mealsEarningsSQL("DESC", this.amountOfData))
  }
  get worstEarnerMeals(){
    return this.getQueryResults(this.mealsEarningsSQL("ASC", this.amountOfData))
  }
  get topEarnerCategories(){
    return this.getQueryResults(this.categoriesEarningsSQL("DESC", this.amountOfData))
  }
  get worstEarnerCategories(){
    return this.getQueryResults(this.categoriesEarningsSQL("ASC", this.amountOfData))
  }
  get biggestAmountMeals(){
    return this.getQueryResults(this.mealsAmountSQL("DESC", this.amountOfData))
  }
  get lowestAmountMeals(){
    return this.getQueryResults(this.mealsAmountSQL("ASC", this.amountOfData))
  }
  get biggestAmountCategories(){
    return this.getQueryResults(this.categoriesAmountSQL("DESC", this.amountOfData))
  }
  get lowestAmountCategories(){
    return this.getQueryResults(this.categoriesAmountSQL("ASC", this.amountOfData))
  }
  get topEarnerDeliveryPlace(){
    return this.getQueryResults(this.earningsInDeliveryPlaceSQL("DESC", this.amountOfData))
  }
  get worstEarnerDeliveryPlace(){
    return this.getQueryResults(this.earningsInDeliveryPlaceSQL("ASC", this.amountOfData))
  }
  get biggestAmountDeliveryPlace(){
    return this.getQueryResults(this.ordersAmountsInDeliveryPlaceSQL("DESC", this.amountOfData))
  }
  get lowestAmountDeliveryPlace(){
    return this.getQueryResults(this.ordersAmountsInDeliveryPlaceSQL("ASC", this.amountOfData))
  }
  get topEarnerPromoCode(){
    return this.getQueryResults(this.earningsUsingPromoCodeSQL("DESC", this.amountOfData))
  }
  get worstEarnerPromoCode(){
    return this.getQueryResults(this.earningsUsingPromoCodeSQL("ASC", this.amountOfData))
  }
  get biggestAmountPromoCode(){
    return this.getQueryResults(this.ordersAmountsUsingPromoCodeSQL("DESC", this.amountOfData))
  }
  get lowestAmountPromoCode(){
    return this.getQueryResults(this.ordersAmountsUsingPromoCodeSQL("ASC", this.amountOfData))
  }
}

const stats = new RestaurantStatsData('stats.db', 3)
console.log("Earnings by days")
console.log(stats.earningsByDays)
console.log("Orders amount by days")
console.log(stats.ordersAmountByDays)
console.log("Earnings by months")
console.log(stats.earningsByMonths)
console.log("Orders amount by months")
console.log(stats.ordersAmountsByMonths)
console.log("Top earning meals")
console.log(stats.topEarnerMeals)
console.log("Worst earning meals")
console.log(stats.worstEarnerMeals)
console.log("Top earning categories")
console.log(stats.topEarnerCategories)
console.log("Worst earning categories")
console.log(stats.worstEarnerCategories)
console.log("Biggest amount of meals")
console.log(stats.biggestAmountMeals)
console.log("Lowest amount of meals")
console.log(stats.lowestAmountMeals)
console.log("Biggest amount of meals by categories")
console.log(stats.biggestAmountCategories)
console.log("Lowest amount of meals by categories")
console.log(stats.lowestAmountCategories)
console.log("Top earning delivery place")
console.log(stats.topEarnerDeliveryPlace)
console.log("Worst earning delivery place")
console.log(stats.worstEarnerDeliveryPlace)
console.log("Biggest amount of orders by delivery_place")
console.log(stats.biggestAmountDeliveryPlace)
console.log("Lowest amount of orders by delivery_place")
console.log(stats.lowestAmountDeliveryPlace)
console.log("Top earning discount code")
console.log(stats.topEarnerPromoCode)
console.log("Worst earning discount code")
console.log(stats.worstEarnerPromoCode)
console.log("Biggest amount of orders by discount code")
console.log(stats.biggestAmountPromoCode)
console.log("Lowest amount of orders by discount code")
console.log(stats.lowestAmountPromoCode)





