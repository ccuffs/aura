var Aura = function(num, another_num) {
    this.axios = require('axios');
    this.changeBar(num);

    this.baz = function(){
        console.log(another_num);
    }
}

Aura.prototype.changeBar = function(num) {
    this.bar = num;
}

var a_secret_variable = 42;

function my_private_function(){
    console.log(a_secret_variable);
}

//All private variables can be normaly used (by functions that can see them).
Aura.prototype.use_magic = function(){
    my_private_function();
}
  
export default Aura;