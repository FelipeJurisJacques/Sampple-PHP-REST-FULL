# Sampple-PHP-REST-FULL
Example of REST-FULL development using native PHP 7.4

# Features
Clean URL with redirect filter;

Good use of the HTTP protocol;

Error tracking;

Developer mode to debug;

Standardization of code in sub layers;

JSON standard;

Various configurable databases;

Good response with performance;

Object oriented;

Classes imported automatically;

No un imported files; 

Simple, native and reduced code;

# USE
URL: http://localhost/server/anotacoes/nota

Method: GET | POST | PUT | DELETE

JSON: {"id":1,"titulo":"sample","mensagem":"test","registro":"2021-04-22 21:45"}

The folder "annotations" is a microservice that is accessed via url. Each microservice has an htaccess that redirects all links to its index where the rest of the link goes through filters;

The filter is the final URL combined with the address to the microservice, special elements in the filter, represent variables:

"<i>": integer;
  
"<d>": floating;
  
"<s>": string;

"<n>": several integers separated by "/";

"<m>": several strings separated by "/";
