# Eduki test task



To understand the level of practical PHP skills we ask candidates to perform a one-hour project.
It’s a real life problem from our project: discount calculation microservice.

Your goal is to implement a “voucher” microservice with two endpoints:
- POST /generate
  - Creates a new voucher, generates and returns a unique voucher code (string) with a given discount (amount of cents).
  - Accepts data in the following format: `{"discount": 100 // integer, amount of cents }`
  - Sample response: `{"code": "ABX72CF"}`
- POST /apply
  - Applies the given voucher to given items. Accepts list of items (array) and voucher code (string). Returns given items with calculated discount. See the rules below.
  - Accepts data in the following format: `{"items": [{ "id": 1, // integer, unsigned, not zero "price": 400 //integer, 1+ (cents) }, { "id": 2, "price": 600 }], "code": "ABX72CF" }`
  - Sample response:`{"items": [{ "id": 1, "price": 400, "price_with_discount": 360 }, { "id": 2, "price": 600, "price_with_discount": 540 }], "code": "ABX72CF"}`



### The following rules apply to discount calculation:

1. Discount should be distributed based on the item's price among all the items. So that each of the items gets the same (as close as possible) relative discount (in %).
   - For example, if there are two items with prices 400 cents and 600 cents, and we’re given a fixed discount of 100 cents (equivalent to 10% of the sum of two items in this case) – you should deduct exactly 40 cents from the first item’s price and 60 cents from the second item’s price. First now costs 360 cents (-10%), second costs 540 cents (-10%). 
2. It’s important that no money is created or lost: the sum of all discounted prices plus effective discount is exactly the sum of original item’s prices.
3. Effective discount should never exceed items total price (the sum of all items prices).
   - In case voucher discount exceeds items total price, the leftover discount amount should be discarded.
   - E.g. in case we have two items with prices 400 cents and 600 cents and we have a discount of 2000 cents. Your application should set price_with_discount to zero for both items and ignore the rest of the discount in this case.
4. Items in response should be in the same order as they were in request
