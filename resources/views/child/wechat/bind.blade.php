<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>绑定成功</title>
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"
          name="viewport"/>
    <meta content="yes" name="apple-mobile-web-app-capable"/>
    <meta content="black" name="apple-mobile-web-app-status-bar-style"/>
    <meta content="telephone=no" name="format-detection"/>
    <style>
        html, body {
            color: #333;
            margin: 0;
            height: 100%;
            font-weight: normal;
        }

        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        a {
            text-decoration: none;
            color: #000;
        }

        a, label, button, input, select {
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }

        img {
            border: 0;
        }

        body {
            background: #fff;
            color: #666;
        }

        html, body, div, dl, dt, dd, ol, ul, li, h1, h2, h3, h4, h5, h6, p, blockquote, pre, button, fieldset, form, input, legend, textarea, th, td {
            margin: 0;
            padding: 0;
        }

        a {
            text-decoration: none;
            color: #08acee;
        }

        button {
            outline: 0;
        }

        img {
            border: 0;
        }

        button, input, optgroup, select, textarea {
            margin: 0;
            font: inherit;
            color: inherit;
            outline: none;
        }

        li {
            list-style: none;
        }

        a {
            color: #666;
        }

        .clearfix::after {
            clear: both;
            content: ".";
            display: block;
            height: 0;
            visibility: hidden;
        }

        .clearfix {
        }


        .divHeight {
            width: 100%;
            height: 10px;
            background: #f5f5f5;
            position: relative;
            overflow: hidden;
        }

        .r-line {
            position: relative;
        }

        .r-line:after {
            content: '';
            position: absolute;
            z-index: 0;
            top: 0;
            right: 0;
            height: 100%;
            border-right: 1px solid #D9D9D9;
            -webkit-transform: scaleX(0.5);
            transform: scaleX(0.5);
            -webkit-transform-origin: 100% 0;
            transform-origin: 100% 0;
        }

        .b-line {
            position: relative;
        }

        .b-line:after {
            content: '';
            position: absolute;
            z-index: 2;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 1px;
            border-bottom: 1px solid #e2e2e2;
            -webkit-transform: scaleY(0.5);
            transform: scaleY(0.5);
            -webkit-transform-origin: 0 100%;
            transform-origin: 0 100%;
        }

        .aui-flex {
            display: -webkit-box;
            display: -webkit-flex;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            align-items: center;
            padding: 15px;
            position: relative;
        }

        .aui-flex-box {
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            flex: 1;
            min-width: 0;
            font-size: 14px;
            color: #333;
        }


        /* 必要布局样式css */
        .aui-flexView {
            width: 100%;
            height: 100%;
            margin: 0 auto;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .aui-scrollView {
            width: 100%;
            height: 100%;
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
            position: relative;
            margin-top: 0;
        }

        .aui-navBar {
            height: 44px;
            position: relative;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            z-index: 1002;
            background: #fff;
        }


        .aui-navBar-item {
            height: 44px;
            min-width: 25%;
            -webkit-box-flex: 0;
            -webkit-flex: 0 0 25%;
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            padding: 0 0.9rem;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            font-size: 0.7rem;
            white-space: nowrap;
            overflow: hidden;
            color: #808080;
            position: relative;
        }

        .aui-navBar-item:first-child {
            -webkit-box-ordinal-group: 2;
            -webkit-order: 1;
            -ms-flex-order: 1;
            order: 1;
            margin-right: -25%;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .aui-navBar-item:last-child {
            -webkit-box-ordinal-group: 4;
            -webkit-order: 3;
            -ms-flex-order: 3;
            order: 3;
            -webkit-box-pack: end;
            -webkit-justify-content: flex-end;
            -ms-flex-pack: end;
            justify-content: flex-end;
        }

        .aui-center {
            -webkit-box-ordinal-group: 3;
            -webkit-order: 2;
            -ms-flex-order: 2;
            order: 2;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            height: 44px;
            width: 50%;
            margin-left: 25%;
        }

        .aui-center-title {
            text-align: center;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            display: block;
            text-overflow: ellipsis;
            font-size: 0.95rem;
            color: #333;
        }

        .icon {
            width: 20px;
            height: 20px;
            display: block;
            border: none;
            float: left;
            background-size: 20px;
            background-repeat: no-repeat;
        }

        .aui-back-pitch {
            width: 50px;
            height: 50px;
            margin: 0 auto;
        }

        .aui-back-pitch img {
            width: 50px;
            height: 50px;
            display: block;
            border: none;
        }

        .aui-back-box {
            height: auto;
            position: relative;
            overflow: hidden;
            text-align: center;
            padding-top: 40px;
            padding-left: 20px;
            padding-right: 20px;
        }

        .aui-back-title {
            padding-top: 30px;
            padding-bottom: 40px;
        }

        .aui-back-title h2 {
            font-size: 16px;
            color: #333;
            padding-bottom: 5px;
        }

        .aui-back-title p {
            font-size: 14px;
        }

        .aui-back-button button {
            width: 100%;
            height: 40px;
            line-height: 40px;
            background: #0d70ff;
            color: #fff;
            border: none;
            border-radius: 3px;
        }

        .yhy_right {
            float: right;
        }
    </style>
</head>
<body>
<section class="aui-flexView">
    <header class="aui-navBar aui-navBar-fixed b-line">
        <div class="aui-center">
            <span class="aui-center-title">绑定成功</span>
        </div>
        <a href="javascript:;" class="aui-navBar-item">
            <i class="icon icon-sys"></i>
        </a>
    </header>
    <section class="aui-scrollView">
        <div class="aui-back-box">
            <div class="aui-back-pitch">
                <img
                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAYAAACtWK6eAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQwIDc5LjE2MDQ1MSwgMjAxNy8wNS8wNi0wMTowODoyMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTggKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OTcwNENCRkRDNDREMTFFOEI5Q0FFRDY3RjY0OUFEQkEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OTcwNENCRkVDNDREMTFFOEI5Q0FFRDY3RjY0OUFEQkEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo5NzA0Q0JGQkM0NEQxMUU4QjlDQUVENjdGNjQ5QURCQSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo5NzA0Q0JGQ0M0NEQxMUU4QjlDQUVENjdGNjQ5QURCQSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PoBBYqwAABl9SURBVHja7F1pkFzVdT7ds89Is6N9lwAhJCEhCa0gxKKSwZAggl34R2ww4CIkOKmynbgSG5ykbFfiVBYwJJjFcaocJwZEil1sAq2DhLbRjCQkjfZ99p596c757rtP6hl1Ty/T/fot55s6NcMwo+m+93zvLPecc32hUIgEAkFk+GUJBILoyDa/uPvzVbIaAkEY3r7pk8sEEaQcPpbxLFNYpurPlSwVWvB1OUseS6H+nZH6odXHEtDf62DpZmlkqWdp0IKvj7Ec1Z/PsARl2dNkQQTDIgIIMJdlTphM1cqf7L6U6a/Nz9Ni/E63Jkt1mOzV35NAUwhiGQpYFrLczLKMZam2BJkGyDhTywNh34fl2cqyhWUjyw6WTtlGIUgqAYuwRssKllwHvXaQ924tQA/LJpb3tFTL9gpBklmXVfpJ/BWWCS56byD3bVr+geUUy7ssr7J8rOMfgRDkCiDlvZLl6yz36yDaCwD5H9WCwP81lv9l+ZSlXwgimMTyEMvD+msvAw+F72g5wfIKy8v6a88+Nb36vu9leZuljuVpIUfEB8dTen2wTn/gRX3x2hvGecPjLAdZ/o/lLpYs4cKQyNLr9IZet8fp8rmNEMQlQCbnJyzHWZ5jmSF6nxRm6PU7rtezXAjibJSEuQk/9lDgbUWs8uMw97RECOIs4DDvr7ywgTZ6AP1Qr7sQxMZA2ceDLAdYfuYFF8BGLuxP9bo/qPdBCGIzLCGjnOK3JBmpTGGSXv8tej+EIDYx8//Ostktm+KihxX2pVQIkjncx1JLxsGWNH/Zz93FvtTofRKCWIjRZJREvM4yTnTR1hin9+k1vW9CkDQDh1boc1gruucorNX7dpcQJD3IZ3mW5S2WUaJvjsQovX/YxwIhSOqAJqDtLE+Qi1KIHo5NsI+f630VgqTANFexzBbdchVm631dKwRJDiiS+zkZjTzFok+uRLHe35+TTYtG7UoQnG28w/KX4lJ5wuXCPr9LNiwJsiNBcBqL4QKrRXc8hTvJ6JWfJASJjgUs28gYkiDwZlyyTeuBEGQQYDE2sIwVPfE0xmo9WC0EuQy0c77JMkL0Q6D14E2tF54nyDfIyGTkil4IwpCr9eJBLxPk2yy/IZmuIogM6MV/aT3xHEG+xfICydAEwdDI0nryLS8R5GssL5KUqAvi11Poy9e9QJA12myK5RAkakl+o/XHtQRZREZfgATkgmQDd+jPYjcSBDNg0ThTKPssGAagP6+SRQPFrSKIWVs1QfZXkKKH7TtkQe2WFQTJ0oyX8hFBKjFH61WW0wmCeUl3yH4K0oA7tH45liC4Z+P7so+CNOL7Ws8cR5DryLhfQvo5BOkE9OtlrW+OIUiB9g9Hyv4JLIDZmVjgFILg7rtZsm8CCzFL653tCfJVMqZWCARW4wmtf7YlCCbnvShxhyCD8ciLlMIJjqkmyC/JgeMlBa4C9O85OxLkDymN6TaBIAGspRTN20oVQUq09RAI7IJnKAVXL6SKIMgeyJR1gZ0wjlKQ1UoFQXBZyqOyHwIb4hEa5qVK/hQQ7BmSrJXAnvBp/fRniiDfZFko+yCwMRZqPbWcIGhc+TtZf4ED8PeUZKPecAjyXZbxsvYChwTs37WSICgO+56su8BBgL6WWEWQvyDj8niBwCmAvv65FQQp0wQRCJyGhB/s/iT/SImstcCBKEnUiiRKEEzdllJ2gZPxBCVwi0CiBHlIYg+BC2KRh9JBEPzsk7K+AhfgyXh1PxGC3MMyQ9ZW4ALM0PqcUoI8JusqcBEeSyVBJpLFU7UFgjQD+jwpVQR5mOQuD4G74Nd6PWyC4Ge+LespcCFiPvjjIcit2sUSCNwG6PWq4RLka7KOAhfjgeEQBLeMyqQSwQAUZBVSRW4lVeZeRYVZRU5/O/fTELcsx7p++TaWSlEJQTiuKbqW5pUspDxfLu1s2U47Wj538tup1Hq+PhmCPCDqIAi3HCDH4tJlNLd4PmX5sulM9xmiFle4WQkTBA3vd4laCPz8UZhdROPzJ9CK8ltpTvE8Gp83njr6OyjPn+eGt3iPDjeCiRBkNsmsKyEHf+T6c2l+8QK6uWIVW5CZVJJdqsgB9+pE5zE3vE2MK8UNzFWJBOl3i3p4G1m+LCrNKaPl5bfQsrKbaeaIWVSWU069oR462XWcqpq3uoUgwJ2RHxDRsVpUxJvw8YciR3YZTS+6mlZVruagfAFV5FTy9/10vvsc7QvsperAbvW1S7A6kRgEN/UsF1XxqFvFJBiZXUyLyhZzzLGKphfOoKKsERRiF7072EPVrbvpg4vvUmtvq5ve9mIyRgN1xEMQ+GO5oireQ76/gEbljaZFpUvohuL5l8gBi9LWF6BdrV/Q3sAeutB9nvpCfW5669B3DJn7LB6CiPXwoFuV7cum0XljaPbIuXTnVWtoTN44yvHlqP/fHeyic91naWPjBjrUfoDjkF43LsMKIYggIkCO8txKWlq2gm6tvJ1G5Y5W3zNxovM4fc5B+ZH2Q9TS2+zWZVgeTwyC84+lojLeATJTEwsmq1Qu3CpYDpADVgWWoqGnXsUdO5qrqLm30W2uVTiWaP0PDUWQKSSDGbwRjPNHXlY+TSmYpmKOlRW3qbSuiWAoSO197XSgrZbjjt30JbtWLke51v+jl9foSswV1fEGQA4c/K0oX6nIgcxVOLo47jjbfZo+qf+Avmw74JVlmRfLxbpBVMf9GJs/XtVVzS9eSNeNuH6A5TBxrOMIbWnaRMc7j1Jbf8ArSzOHZd1QBJkj6uNeIF07InskzWJSLC+7hWYX36DSuOHoD/VTe38b1bTto61NGznuaFLf8xBBhrQgs0WN3AuQY2X5bSrmuHbEdVTgv/LaDJBja9NmFZgjQHdxUE6x9H8wQfDf00WN3AXzjAOZqlkjZ9PismU0uWDqFZYDaO1roaPsWu1oqVKulUvPO4bCdM2DvkgEQY9ujqiUu8iBatyrckfT/JKFquhwSuE0yvfnD/i5EH/AUpzuOkV72XIcCNRQY2+DF5cM+o9xQHWRCDJNVMpdgOUAOe4ZfR9dP3KOCs5BmMEAOeBO7WrZQZ82fEyBvlYvL9vUaASZJCrlDuCMIz8rn6YXXUPzim+kORyMj8obc4XlMINynI7vaN5GNYFqqu+54LW4g6LxYDBBJohqOR/IVKE9dkL+RFpcupSWsluFIQtmXVWkoPxU1wna1ryF6joOezHuoGg8GEyQUaJezgeC72mF0+neMWv589XqjCO8rmowQIqNDRtU8xPIIrjMg8GrNlbWxtmWA+RAzzgsx9W6PRbfj4SeYA+d7zmn3Co0QCHu8NB5x1AYE40gUoPlUCDwHplVTJMLp9KS0mW0rPwW9T1/lKZRECHQ30q1TIzatn3KxRJcQkU0gpTJ2jjUJ8gdowLx2ytXc+wxaUhymHEHXKqNjZ9SXfthWcCBKI1GkFJZG+dZDpBjQclNtLhsKU0umEaFWYVRf94870DcUdW0hU52Hpe4IwGCFMraOAdoj8X4T/RwgBw3FN8Y83dAjvqeiyru2N68TaV3Je64MpyLRpA8WRvnYFrRDBWMLyxZrM444gH6yj9t+EgdCHqwzipeFEcjyEhZG/vDHAEKYqB8BKfj8Uw4hOX4sm2/KiVBSYmcd0SFLxpB/N5aBd+lQzUoGMbddPZ3qgEFSIHaDQi68VrHMSHQ5DSHXauJ+bGLH8y4A8WHGDSNYkQUJQqiojAaQTwFHJ6V5JTS7JE3qPE26KirDuyhg/yUtVva0xwBijMOFBxi8gh6yeOBWWdV3bqHtjZtkqA8NnI8TZA8fz5NLphCk5RMVp8xCwrfR0kGyHK885jK9GByYKaftrByOPCbX7LA6AAcOZvKcyuilo6EAwE4Gp7Q+FTTVi2WIz70RSNI0AtuFp7EIMWyshU0t2S+IoZ5ZjAmbyzNHHE9E+Osetrub9unRt5gWHNPsNvSoBYuINw+jABFQI4RoCBvcXZJ3P8GWmVPsGu1mQlyvKNOVD8+tEcjCBqPS9z+7hFfHOusUx11lwe8XAYqXsflT6A1o75Kc9mlOdz+JX3Rsl358HBVLHOroowATQQYtrCh4UM623Wauvq7RPXjQ9SxP92esJ+hXjU6c3frTkUGtJ+GT/SAYub58lTgDsETuyL3KnXyDJJA6Vr6mtMayA81AjQeINEAy6ean9pqqb2vjd2DoKh+fGiNRpB2L7x7+OXwxdFz3cmuE9KkUwqmqgzRYEApiwpGKJesobee6toPKRfteMdRauxtVK5XX7A3ZcoXawRovEF5E8cdGPS2L7BHjQwVJKYi0QjSSEY3lTcczf42ZRE+qn+fbi6/NeZJNALlWay0E5ksSJXWBqppZ+t2ZY1AlFQg1gjQeADrdqTjEFU1G6UkgoTREI0gnmpCVhWtfa0q/ZnrMw7aUCIerZYJipqdlc1WpUi5XqU5pTShYCLHKIeUQp7uPEWdwY6kSzfCR4DOHTQCND7H2TjvgAuI/o6z3WfU8DdBwmiMRpB6r60E4gjzzAM3J8Glwl18BVmFMZUZct2I2TS18ABVtoyiav8uOt9zXpEOrhuIEoqUBRgcjMcYAZqIa3WRrRnOcrY3b7XlYadDUB+NIBe9uiIXes6pO/cAnFLHU/hnAvEJbl9aVLpYXQ0Ai1Qd2EstHAfEU84RPgJ0SdnyK0aAxh1Z9rbQ+vp3VWwFckhQnnqCNHh1RaBQGHODrA+yVhCkeuOpcUImLC83j4pzStjyFLBlqWArNImOdh5RwTzIF+1pjmB8euHVynLMYmuUjOUAcKC5v62GapiY+FrIkR6CnPLyqpjuFu7eM+KMfFVOHmlMTqTsEzJNY/PGq/4MWAQo7J7snbS/vYYaexo4kG+/9GQ3R4DiLGZJ6Qq6sWQRW47Ea0Xxb+HfxKk/3KqTnSeklGT4OBWNINJ3yTjSfpi6+juV6zM3zoLAATGFPuDDIePUwum0omcl7W7ZRbtat9OxjjoVOJsjQBeyW3ZN0XVDNjnFIjWs1M6WHeowEyQUDBsnoxFEahEIY/876Uz3Gdrc+Kk640B/2Wi2CvFYEtOamAMUMPsW7hqs0ai8UepJj/IP/D9zBGgylsMkR333BVUSc4CtlceHvaUSddEIAuYgqvT8+FFkofa07lLqnsPEyOcYAVmrRM8kYE1gHa4uulYVSMKlautrUwSKNAI0EaAIEWUwuBrtTNcpUevUoDfckxq826jEO8IyU9bJAG5VgkuEsviZI2apbFWyANHG5o2j/twg+Xy+uC1SNCDGeb/+HZmEmGIPm4ao5gX2CUEGWpLTXSdpY8Mn1B/so3klCxKqiRrseuX6h9/VDBcQrhrKSMyYJp7zFkFc2DfAA4jwA9WyRgPR0deuBhygGw+KiSxRpgYdGJdqNlBV01ZlQVBT5qWhCz79kUZUxyLIXqHEQJipVAw6eOPca5dIkgmg3B4B+RdMVi/GHVn+rHRby5gE2SOUiEwSVQTIQfHH9evVgaKVT2+zAnl/YJ/KWqFAsjvY7bl98Ke/n2+A/keKQY6RUawlY0gjKCkyR5sbPyM/xyAoVpxWOEPVbaXT7OOJafR3HKM9gV3KkkmdVVrQRGFXQEcjCOzXVpa7Zb2iu1u1/CTP1r3iY/IT69dIFGroQm89bWj4SP1dL9dZ4e72NGIrDeoxjWavtggVhiYJYoHaQI1S2kPtB1XwnC7fGLHGzuYdatoK/q6X66zSnM7ePPgb0U69NgkNhoY6bWfFRb83UJxdTFfljlJp3FS5W3hawrU6pA4Dt+j+jk7PrjkOXdNsQTZdGfNEBuq+xcmNx/XhJzqC5jfPraMLKT6wAzm+VOXzu+ggf+72+NCFRKsYEgT0fUe8BOkUNyu+4BmuFcrLMXOqqmlzylpcO9lSwGLgvAMWBAeWXi9hT3NiooqlI16CAO8LBeJ3tzBWB5YEQXTzMCemh5gIjWyZ0DqLnneps7IE6yO6dUP8wjuyZok93VD2AZJgenqylbUgR7fKktWo+EYmsFuGDyK6dUP8Ak4Uz7CMk7WLI6DmD9REYUoKgnR0ImJsz4SCxHpJMB0FLbN7AjvV+NNuqbNSsUeaHxIXdNydEEFC2oo8IuofP5p6GxVRQBhkXdCGG29xI0ab4oRcjTxlV03m6BpIc+0V8DZR5AAvVlrg90KQxIFsE7JPsCJ4yiwtWx7XPF3c2YHBEfvbai0dcWp3WOBi/k9U6xXjFz8ho4G9UrYpMXcLWScUNWaxe1CRW6EGM0S7rgABPYofUYSIYW8gh1xuY5l7Bf3+OFmCYJfWsTyabhPqRj8bNzphDjBabeFiqeknmCTv84f5sUad1TGOXTAqCHcHCmjA+qQZ67SeJ0UQ0/w86vBFyBgC6k7AjxUJUIk6eHKjeanm+ovvqKsWBFda10y5V/ESZAMZveoTxYokt8GoAN6nLQNKJdCLbrpbpzpPqN6Ow+0H1c8JDMDKhkJG2juNOKX1e1gEAYVfZnkq3VbErSQxiYAbZnGtQbY/m61IkcpaoSsQWauL3RekhH3QAzNIabceLxEN/UfiLW4BQX5Eab59ys0EAXBHx9amzYocGAeEMxP0duCAUchhudsN0/RKzCRBnP8YxqC8x3JXel9xUDUiBV3aY414AyUkGPB2seeCylZhIIRMYB+IHH8O9QbTnsWDPh9PFUGAF9JNEPXkCLm3IM8sbjzYVqtEkLHA3NTn2LFQAv/gmyyHXWBaBTZHMP0PycNan1NKELzqZ6wL0vyiKR5Dls+yW8l/SRRfeixRLUSwbkku0ucThfESjHorS7wH6O+L8f5wogTBMKhnrTKzFhSpCWxEEItij2e1HqeFIMC/sFhSZirxiIfiDmu6JVu1/lI6CdKY6B8ZvukVuNZy+HxW7vE/U9gFnekiSFJ/aDhWJLy4T+AuoD7NIk+hUestWUEQuFi/sMz8SjziSiBrZeHg7X9KJjQYzqP538hoybUEEo+4MSi3rNceevqvyVm45IHL8P5GtlrggLjyR1pfLSUI8J8UYdiWBO2CWIG5hR4B9PPXycdIwwwPWP6MyLp3K66Ww4Py9I8PHaguhn4GM0UQYBsZJ+wCQewnqrXFqC9r/aRMEgT4ActF2X5BNCRzp+MwcVHrJdmBIMgxPylqIIhGjgzco/gkpeCsLpUncL8jY0KEQDAAGSDHOq2PZCeCAI+znM/EJkh2y37IUMsC9O9PUpZUSMOLe4TI+lSTOfRBYBdy+NI9kSSyGhj6d86uBAHeYnk+E5siKWD7IEN78bzWP7IzQYDvsWSs6dqCy+YF9kOt1jtyAkFwQ9UfsQQy9fQyq4CFKJ5AQOtbp1MIAuxneTgT8YgJdSgl/HC/N2fo2f50/OPpTjO8ShaWxUdcvVAoE4dUAuvwC61n5ESCAD9k+TCTK2jm4dGcIy6Xq/Ch1i9yMkH6tX+Y8bn+Xr8l1mWo1nrV73SCAOjkwlTG05l3WI2QSNp4HY2zWp/SPjzESi3BqPn7KcJd1JkK4OFuIT7xCVmcBOjPfVqfyE0EAaq0WbTFKHNYExWfhOSA0SHo0fpTZdUfzMSj812WP06375iM2+WTIN7OgL58U+sPuZkgAK69eoTIXlEzaofMA0Y5ZLQVglpffmf1H87O4Jv+NQsOKP5Df7bPboQk22Uzy/EdGkZfuRMtiImXtLvVZ/ddEmuSEfRp/XgpUy/ADumb37I8YJfAPVacAiDzJWliSwLyB7R+kJcJArzBcg8lObvIcpsf6hc3LL1o1/rwRqZfiJ0eg+tZVlIKm10ys6BiWYaJc1oP1ttjP+2FL1gWs9Q4dXcjlbPAHYNbZspw3DOfSkZf/nBZbFSj9/8L+zzw7AfcqLuc5QO37DrcMbhlpgzHPQupZPTlDxd1UX6o9/2EvTwCewI1Nl9h+Uci6aN1OUJ6n9eQRRczuYEgKhYmY/AXMhkB0SNXIqD39wdko8oKpxDExGssN1EGe9wFaUGt3tfX7PwinZJyOcCykIzre8Xlcr5L9ZzezwN2f7FOykmiIf9PWe5luSB65khc0Pv3BKVhwILXCWICc4/mkow5dRre0Pv2lpNetFNPtTDBcS0ZDVhnRPdsjTN6n+6jDI2l9SJBTLzOcj3LryQ2sWWs8Su9P6879U24oS6imeUxlmU0zMtSBCnDNr0fj+n9ISGIfTblG2Sz01gP4aRef9c8rNxWWQez/t/arP81peACFUFcaNLrPUuvv2vcXbeWnrax/JRlGsvTZMMSBpcA6/oTlql6vdvc9gb9HtlAEOVvWepFp1OCer2ern8AeaV5Aa7WUyyTybh96LDoeFI4rNdvsl5P17uwXuvuwdAxXLJyLRnnKBghI62BQyOo12mtXrfnySbD/6xAtoc3fZ2WSWSMz39Ify0wgIzUK2QMTPBsVlD6Q43Nf1r703ewvODhWKWBjMO9O3Tg/RR5PGWeLfy4BPQjfKQFxXS3k9GrcDfLGBe/b/SAv83ye/3e+0QVhCCxACV5XwuavueR0fEGWcqS4+D31suyleU9LbtJynSEIMMAlGeXlp+xFLAsYrlZkwVSbuPX36gJAdnIsp0cUmouBHEmoFyfaSFtYeCvo5R7TthnfC/XwteFQWtHybhYZq/+DKkTCyEEybSFqdMSPugMCZBxLFM0WSCVLBVa8HUZS762SkCJ/j1k2VrCCNlFRjlHvQ6kTanTpDhGRlm5pKyFII4BlPWUlk2yHM6ELySXxwgEUfH/AgwA/GH17Ird8vcAAAAASUVORK5CYII="
                    alt="">
            </div>
            <div class="aui-back-title">
                <h2>绑定成功！</h2>
                <p>恭喜您绑定成功！</p>
            </div>
            <div class="aui-back-button">
            </div>
        </div>
    </section>
</section>
</body>
</html>
