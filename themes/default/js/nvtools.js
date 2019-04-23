/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 19 Mar 2011 16:50:45 GMT
 */

function nv_additem_admin() {
    items_admin++;
    var newitem = '<tr>';
    newitem += '    <td class="text-center">' + items_admin + '</td>';
    newitem += '    <td><input type="text" name="adminfile[' + items_admin + ']" class="form-control" /></td>';
    newitem += '    <td><input type="text" name="admintitle[' + items_admin + ']" class="form-control" /></td>';
    newitem += '    <td><input type="text" name="admintitlevi[' + items_admin + ']" class="form-control" /></td>';
    newitem += '    <td class="text-center"><input type="checkbox" name="adminajax[' + items_admin + ']" /></td>';
    newitem += '</tr>';
    $("#adminitems").append(newitem);
}

function nv_additem_site() {
    items_site++;
    var newitem = '<tr>';
    newitem += '    <td class="text-center">' + items_site + '</td>';
    newitem += '    <td><input type="text" name="sitefile[' + items_site + ']" class="form-control" /></td>';
    newitem += '    <td><input type="text" name="sitetitle[' + items_site + ']" class="form-control" /></td>';
    newitem += '    <td><input type="text" name="sitetitlevi[' + items_site + ']" class="form-control" /></td>';
    newitem += '    <td class="text-center"><input type="checkbox" name="siteajax[' + items_site + ']" /></td>';
    newitem += '</tr>';
    $("#siteitems").append(newitem);
}

function nv_additem_sql() {
    items_sql++;
    var newitem = '<tr>';
    newitem += '    <td class="text-center">' + items_sql + '</td>';
    newitem += '    <td><input type="text" name="tablename[' + items_sql + ']"  class="form-control"/>';
    newitem += '        <textarea id="idsqltablehidden_' + items_sql + '" name="sqltablehidden[' + items_sql + ']" class="hidden"></textarea>';
    newitem += '    </td>';
    newitem += '    <td><textarea id="idsqltable_' + items_sql + '" class="form-control" rows="9"></textarea></td>';
    newitem += '</tr>';
    $("#sqlitems").append(newitem);
}

function theme_additem_position() {
    items_positions++;
    var newitem = '<tr>';
    newitem += '    <td class="text-center">' + items_positions + '</td>';
    newitem += '    <td><input type="text" name="position_tag[' + items_positions + ']" style="width:220px;" /></td>';
    newitem += '    <td><input type="text" name="position_name[' + items_positions + ']" style="width:220px;" /></td>';
    newitem += '    <td><input type="text" name="position_name_vi[' + items_positions + ']" style="width:220px;" /></td>';
    newitem += '</tr>';
    $("#additem_position").append(newitem);
}

/*!
 * jquery.base64.js 0.0.3 - https://github.com/yckart/jquery.base64.js
 * Makes Base64 en & -decoding simpler as it is.
 *
 * Based upon: https://gist.github.com/Yaffle/1284012
 *
 * Copyright (c) 2012 Yannick Albert (http://yckart.com)
 * Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php).
 * 2013/02/10
 **/
;
(function($) {

    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
        a256 = '',
        r64 = [256],
        r256 = [256],
        i = 0;

    var UTF8 = {

        /**
         * Encode multi-byte Unicode string into utf-8 multiple single-byte characters
         * (BMP / basic multilingual plane only)
         *
         * Chars in range U+0080 - U+07FF are encoded in 2 chars, U+0800 - U+FFFF in 3 chars
         *
         * @param {String} strUni Unicode string to be encoded as UTF-8
         * @returns {String} encoded string
         */
        encode: function(strUni) {
            // use regular expressions & String.replace callback function for better efficiency
            // than procedural approaches
            var strUtf = strUni.replace(/[\u0080-\u07ff]/g, // U+0080 - U+07FF => 2 bytes 110yyyyy, 10zzzzzz
                    function(c) {
                        var cc = c.charCodeAt(0);
                        return String.fromCharCode(0xc0 | cc >> 6, 0x80 | cc & 0x3f);
                    })
                .replace(/[\u0800-\uffff]/g, // U+0800 - U+FFFF => 3 bytes 1110xxxx, 10yyyyyy, 10zzzzzz
                    function(c) {
                        var cc = c.charCodeAt(0);
                        return String.fromCharCode(0xe0 | cc >> 12, 0x80 | cc >> 6 & 0x3F, 0x80 | cc & 0x3f);
                    });
            return strUtf;
        },

        /**
         * Decode utf-8 encoded string back into multi-byte Unicode characters
         *
         * @param {String} strUtf UTF-8 string to be decoded back to Unicode
         * @returns {String} decoded string
         */
        decode: function(strUtf) {
            // note: decode 3-byte chars first as decoded 2-byte strings could appear to be 3-byte char!
            var strUni = strUtf.replace(/[\u00e0-\u00ef][\u0080-\u00bf][\u0080-\u00bf]/g, // 3-byte chars
                    function(c) { // (note parentheses for precence)
                        var cc = ((c.charCodeAt(0) & 0x0f) << 12) | ((c.charCodeAt(1) & 0x3f) << 6) | (c.charCodeAt(2) & 0x3f);
                        return String.fromCharCode(cc);
                    })
                .replace(/[\u00c0-\u00df][\u0080-\u00bf]/g, // 2-byte chars
                    function(c) { // (note parentheses for precence)
                        var cc = (c.charCodeAt(0) & 0x1f) << 6 | c.charCodeAt(1) & 0x3f;
                        return String.fromCharCode(cc);
                    });
            return strUni;
        }
    };

    while (i < 256) {
        var c = String.fromCharCode(i);
        a256 += c;
        r256[i] = i;
        r64[i] = b64.indexOf(c);
        ++i;
    }

    function code(s, discard, alpha, beta, w1, w2) {
        s = String(s);
        var buffer = 0,
            i = 0,
            length = s.length,
            result = '',
            bitsInBuffer = 0;

        while (i < length) {
            var c = s.charCodeAt(i);
            c = c < 256 ? alpha[c] : -1;

            buffer = (buffer << w1) + c;
            bitsInBuffer += w1;

            while (bitsInBuffer >= w2) {
                bitsInBuffer -= w2;
                var tmp = buffer >> bitsInBuffer;
                result += beta.charAt(tmp);
                buffer ^= tmp << bitsInBuffer;
            }
            ++i;
        }
        if (!discard && bitsInBuffer > 0) result += beta.charAt(buffer << (w2 - bitsInBuffer));
        return result;
    }

    var Plugin = $.base64 = function(dir, input, encode) {
        return input ? Plugin[dir](input, encode) : dir ? null : this;
    };

    Plugin.btoa = Plugin.encode = function(plain, utf8encode) {
        plain = Plugin.raw === false || Plugin.utf8encode || utf8encode ? UTF8.encode(plain) : plain;
        plain = code(plain, false, r256, b64, 8, 6);
        return plain + '===='.slice((plain.length % 4) || 4);
    };

    Plugin.atob = Plugin.decode = function(coded, utf8decode) {
        coded = coded.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        coded = String(coded).split('=');
        var i = coded.length;
        do {
            --i;
            coded[i] = code(coded[i], true, r64, a256, 6, 8);
        } while (i > 0);
        coded = coded.join('');
        return Plugin.raw === false || Plugin.utf8decode || utf8decode ? UTF8.decode(coded) : coded;
    };
}(jQuery));

$(function() {
    $('body').delegate('[data-toggle="chooseAll"]', 'click', function(e) {
        e.preventDefault();
        var tg = $(this).data('target');
        var all = $(this).data('all');
        var callback = $(this).data('callback');
        if (all) {
            $(tg).prop('checked', false);
            $(this).data('all', false);
        } else {
            $(tg).prop('checked', true);
            $(this).data('all', true);
        }
        if (typeof callback != "undefined") {
            window[callback]();
        }
    });
});
