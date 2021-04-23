
# Sampple-PHP-REST-FULL <br>
Example of REST-FULL development using native PHP 7.4 <br>
 <br>
# Features <br>
Clean URL with redirect filter; <br>
Good use of the HTTP protocol; <br>
Error tracking; <br>
Developer mode to debug; <br>
Standardization of code in sub layers; <br>
JSON standard; <br>
Various configurable databases; <br>
Good response with performance; <br>
Object oriented; <br>
Classes imported automatically; <br>
No un imported files;  <br>
Simple, native and reduced code; <br>
 <br>
# USE <br>
URL: http://localhost/server/anotacoes/nota <br>
Method: GET | POST | PUT | DELETE <br>
JSON: {"id":1,"titulo":"sample","mensagem":"test","registro":"2021-04-22 21:45"}

The folder "annotations" is a microservice that is accessed via url. Each microservice has an htaccess that redirects all links to its index where the rest of the link goes through filters;

The filter is the final URL combined with the address to the microservice, special elements in the filter, represent variables: <br>
"\<i\>": integer; <br>
"\<d\>": floating; <br>
"\<s\>": string; <br>
"\<n\>": several integers separated by "/"; <br>
"\<m\>": several strings separated by "/";

The "Address" file is defined as global variables (constants) within the abstract class, the same file can be used for initial configurations (in this example it configures the database for the entire project)
