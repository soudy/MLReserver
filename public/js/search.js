/*
 * search.js
 * Copyright (C) 2015 soud
 *
 * Distributed under terms of the MIT license.
 */

/*jslint browser:true */
function search(query)
{
    var search_url = url + 'item/search/' + query;
    var xmlhttp;

    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            console.log(xmlhttp.responseText);
        }
    };

    xmlhttp.open("GET", search_url, true);
    xmlhttp.send();
}

(function()
{
    document.querySelector('#search_item').onkeyup = function() {
        search(this.value);
    };
}());
