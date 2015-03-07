/*
 * MLReserver is a reservation system primarily made for making sharing items
 * easy and clear between a large group of people.
 * Copyright (C) 2015 soud
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

(function()
{
    var menu_items = {
        all_items        : document.querySelector('#all_items'),
        all_reservations : document.querySelector('#all_reservations'),
        my_reservations  : document.querySelector('#my_reservations'),
        my_requests      : document.querySelector('#my_requests'),
        all_requests     : document.querySelector('#all_requests'),
        items            : document.querySelector('#items'),
        users            : document.querySelector('#users')
    };

    for (var item in menu_items) {
        if (!menu_items[item])
            continue;

        if (menu_items[item].className.indexOf('dropdown') > -1)
            menu_items[item].className = 'dropdown';
        else
            menu_items[item].className = '';
    }

    var url = document.URL;
        url = url.split('/');

    /*
     * Change this depending on what your url looks like.
     * For example: http://127.0.0.1/MLReserver/item/all will require this to be
     * set to url[4], while http://127.0.0.1/item/all requires this to be set to
     * url[3].
     */
    url_offset = 4;

    cat = url[url_offset].toLowerCase();

    switch (cat) {
        case 'item':
            if (url[url_offset + 1] === 'all' || url[url_offset + 1] === 'add')
                menu_items.items.className += ' active';
            else
                menu_items.all_items.className += ' active';
            break;

        case 'user':
            if (url[url_offset + 1] !== 'settings')
                menu_items.users.className += ' active';
            break;

        case 'reserve':
            if (url[url_offset + 1] === 'all' || url[url_offset + 1] === 'calender')
                menu_items.all_reservations.className += ' active';
            else if (url[url_offset + 1] === 'user')
                menu_items.my_reservations.className += ' active';
            else
                menu_items.all_items.className += ' active';
            break;

        case 'request':
            if (url[url_offset + 1] === 'user')
                menu_items.my_requests.className += ' active';
            else if (url[url_offset + 1] === 'request')
                menu_items.all_items.className += ' active';
            else
                menu_items.all_requests.className += ' active';
            break;

        default:
            menu_items.all_items.className += ' active';
    }

})();
