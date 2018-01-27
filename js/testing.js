$(document).ready(function () {
    var apple = {
        type="mac",
        color="red",
        getInfo=function () {
            return this.color + ' ' + this.type + ' Apple';
        }
    }

apple.color = "green";
    document.write("Output");
    document.write(apple.getInfo() + '<br>');    
});
