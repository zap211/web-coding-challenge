<?php

/* WebProfilerBundle:Profiler:base_js.html.twig */
class __TwigTemplate_10270eb8f1356371bf4ae38ca51c0f89 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<script type=\"text/javascript\">/*<![CDATA[*/
    Sfjs = (function() {
        \"use strict\";

        var noop = function() {},
            request = function(url, onSuccess, onError, payload, options) {
                var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                options = options || {};
                xhr.open(options.method || 'GET', url, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onreadystatechange = function(state) {
                    if (4 === xhr.readyState && 200 === xhr.status) {
                        (onSuccess || noop)(xhr);
                    } else if (4 === xhr.readyState && xhr.status != 200) {
                        (onError || noop)(xhr);
                    }
                };
                xhr.send(payload || '');
            },
            hasClass = function(el, klass) {
                return el.className.match(new RegExp('\\\\b' + klass + '\\\\b'));
            },
            removeClass = function(el, klass) {
                el.className = el.className.replace(new RegExp('\\\\b' + klass + '\\\\b'), ' ');
            },
            addClass = function(el, klass) {
                if (!hasClass(el, klass)) { el.className += \" \" + klass; }
            };

        return {
            hasClass: hasClass,
            removeClass: removeClass,
            addClass: addClass,
            request: request,
            load: function(selector, url, onSuccess, onError, options) {
                var el = document.getElementById(selector);

                if (el && el.getAttribute('data-sfurl') !== url) {
                    request(
                        url,
                        function(xhr) {
                            el.innerHTML = xhr.responseText;
                            el.setAttribute('data-sfurl', url);
                            removeClass(el, 'loading');
                            (onSuccess || noop)(xhr, el);
                        },
                        function(xhr) { (onError || noop)(xhr, el); },
                        options
                    );
                }

                return this;
            },
            toggle: function(selector, elOn, elOff) {
                var i,
                    style,
                    tmp = elOn.style.display,
                    el = document.getElementById(selector);

                elOn.style.display = elOff.style.display;
                elOff.style.display = tmp;

                if (el) {
                    el.style.display = 'none' === tmp ? 'none' : 'block';
                }

                return this;
            }

        }
    })();
/*]]>*/</script>
";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Profiler:base_js.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  42 => 13,  29 => 6,  70 => 12,  30 => 8,  19 => 2,  1174 => 331,  1168 => 330,  1162 => 329,  1156 => 328,  1150 => 327,  1144 => 326,  1138 => 325,  1132 => 324,  1126 => 323,  1110 => 317,  1103 => 316,  1101 => 315,  1098 => 314,  1075 => 310,  1050 => 309,  1048 => 308,  1045 => 307,  1033 => 302,  1028 => 301,  1026 => 300,  1023 => 299,  1014 => 293,  1008 => 291,  1005 => 290,  1000 => 289,  998 => 288,  995 => 287,  988 => 282,  981 => 280,  978 => 276,  974 => 275,  971 => 274,  968 => 273,  966 => 272,  963 => 271,  955 => 267,  953 => 266,  950 => 265,  943 => 260,  940 => 259,  932 => 254,  928 => 253,  924 => 252,  921 => 251,  919 => 250,  916 => 249,  908 => 245,  906 => 241,  904 => 240,  901 => 239,  880 => 233,  877 => 232,  874 => 231,  871 => 230,  868 => 229,  865 => 228,  862 => 227,  859 => 226,  856 => 225,  853 => 224,  851 => 223,  848 => 222,  840 => 216,  837 => 215,  835 => 214,  832 => 213,  824 => 209,  821 => 208,  819 => 207,  816 => 206,  808 => 202,  805 => 201,  803 => 200,  800 => 199,  792 => 195,  789 => 194,  787 => 193,  784 => 192,  776 => 188,  773 => 187,  771 => 186,  768 => 185,  760 => 181,  757 => 180,  755 => 179,  752 => 178,  744 => 174,  742 => 173,  739 => 172,  731 => 168,  728 => 167,  726 => 166,  723 => 165,  715 => 161,  712 => 160,  710 => 159,  708 => 158,  705 => 157,  698 => 152,  690 => 151,  685 => 150,  679 => 148,  676 => 147,  674 => 146,  671 => 145,  663 => 139,  661 => 138,  660 => 137,  659 => 136,  658 => 135,  653 => 134,  647 => 132,  644 => 131,  642 => 130,  639 => 129,  630 => 123,  626 => 122,  622 => 121,  618 => 120,  613 => 119,  607 => 117,  604 => 116,  602 => 115,  599 => 114,  583 => 110,  581 => 109,  578 => 108,  562 => 104,  560 => 103,  557 => 102,  540 => 98,  528 => 96,  521 => 93,  519 => 92,  514 => 91,  511 => 90,  493 => 89,  491 => 88,  488 => 87,  479 => 82,  476 => 81,  473 => 80,  467 => 78,  465 => 77,  460 => 76,  457 => 75,  454 => 74,  448 => 72,  446 => 71,  438 => 70,  436 => 69,  433 => 68,  427 => 64,  419 => 62,  414 => 61,  410 => 60,  405 => 59,  403 => 58,  400 => 57,  391 => 52,  385 => 50,  382 => 49,  380 => 48,  377 => 47,  367 => 43,  365 => 42,  362 => 41,  354 => 37,  351 => 36,  348 => 35,  345 => 34,  343 => 33,  340 => 32,  332 => 27,  327 => 26,  321 => 24,  319 => 23,  314 => 22,  312 => 21,  309 => 20,  293 => 16,  290 => 15,  288 => 14,  285 => 13,  276 => 8,  270 => 6,  267 => 5,  265 => 4,  262 => 3,  258 => 331,  256 => 330,  254 => 329,  252 => 328,  250 => 327,  248 => 326,  246 => 325,  244 => 324,  242 => 323,  239 => 322,  236 => 320,  234 => 314,  231 => 313,  229 => 307,  226 => 306,  224 => 299,  221 => 298,  218 => 296,  216 => 287,  213 => 286,  211 => 271,  208 => 270,  206 => 265,  203 => 264,  200 => 262,  198 => 259,  195 => 258,  188 => 239,  185 => 238,  182 => 236,  180 => 222,  177 => 221,  174 => 219,  169 => 212,  167 => 206,  164 => 205,  157 => 192,  144 => 177,  142 => 172,  139 => 171,  137 => 165,  132 => 157,  129 => 156,  127 => 145,  124 => 144,  122 => 129,  119 => 128,  117 => 114,  114 => 113,  112 => 108,  107 => 102,  94 => 67,  84 => 46,  82 => 41,  79 => 40,  77 => 32,  72 => 20,  69 => 19,  108 => 32,  95 => 29,  92 => 57,  89 => 56,  71 => 20,  61 => 17,  58 => 10,  49 => 10,  33 => 7,  40 => 7,  20 => 2,  199 => 7,  197 => 6,  193 => 249,  190 => 248,  172 => 213,  165 => 86,  162 => 199,  154 => 191,  147 => 178,  145 => 79,  128 => 72,  123 => 69,  121 => 68,  115 => 64,  111 => 58,  109 => 107,  103 => 53,  99 => 86,  97 => 68,  86 => 26,  80 => 22,  73 => 35,  45 => 9,  43 => 23,  35 => 19,  24 => 3,  18 => 1,  62 => 3,  53 => 16,  46 => 8,  36 => 9,  32 => 8,  27 => 7,  41 => 12,  34 => 6,  22 => 2,  17 => 1,  159 => 198,  156 => 55,  152 => 185,  149 => 184,  146 => 50,  140 => 34,  134 => 164,  104 => 101,  102 => 87,  93 => 57,  91 => 55,  87 => 47,  85 => 50,  76 => 13,  74 => 31,  66 => 35,  64 => 11,  60 => 33,  56 => 28,  52 => 9,  21 => 2,  81 => 30,  75 => 36,  67 => 13,  63 => 23,  59 => 2,  50 => 27,  44 => 13,  39 => 10,  37 => 3,  31 => 5,  28 => 5,  23 => 4,);
    }
}