/*
 * search.js
 * Copyright (C) 2015 soud
 *
 * Distributed under terms of the MIT license.
 */

/*jslint browser:true */
function ajax(url)
{
    var xmlhttp;

    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            console.log(xmlhttp.responseText);
        }
    };

    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

(function()
{
    document.querySelector('#search_item').onkeyup = function() {
        ajax(url + 'item/search/' + this.value);
    };
}());
