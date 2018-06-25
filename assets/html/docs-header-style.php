<style>
    body {
        margin: 0px;
        padding: 0px;
        font-family: Calibri;
    }
    input:focus,
    select:focus,
    textarea:focus,
    button:focus {
        outline: none;
    }
    input[type=text], input[type=date], input[type=time], input[type=datetime], input[type=number], select {
        min-width: 180px;
        padding: 12px;
        border: 1px solid #446894;
        border-radius: 2px;
        color: #446894;
        height: 48px;
    }
    input[type=submit], input[type=button], button {
        min-width: 140px;
        padding: 12px; 
        border: 1px solid #446894;
        background-color: #446894;
        border-radius: 2px;
        color: #f9f9f9;
        height: 48px;
    }
    .hidden-large-screen {
        display: none;
    }
    .hidden-short-screen {
        display: inherit;
    }
    .page {
        display: flex;
    }
    .side-menu {
        width: 260px;
        float: left;
        min-height: 100%;
        -webkit-box-shadow: -5px 0px 13px 2px rgba(0,0,0,0.75);
        -moz-box-shadow: -5px 0px 13px 2px rgba(0,0,0,0.75);
        box-shadow: -5px 0px 13px 2px rgba(0,0,0,0.75);
        z-index: 10;
        background-color: #f9f9f9;
    }
    .side-menu .container {
        margin-left: 26px;
        display: block;
    }
    .side-menu .container .title, .side-menu .container .title a, .side-menu .container .title a:hover {
        font-size: 26px;
        font-weight: 400;
        color: #446894;
        margin-top: 10px;
        margin-bottom: 10px;
        text-decoration: none;
    }
    .side-menu .container .subtitle {
        font-size: 18px;
        margin-bottom: 10px;
        font-weight: 400;
        line-height: 38px;
        border-bottom: 2px solid #333333;
        color: #333333;
    }
    .side-menu .container .menu {
        font-size: 16px;
        margin-top: 8px;
        margin-bottom: 8px;
        font-weight: 400;
        margin-left: 12px;
    }
    .side-menu .container .menu .link {
        margin-top: 6px;
        margin-bottom: 6px;
    }
    .side-menu .container .menu .link a {
        color: #3c9e95;
        text-decoration: none;
        font-weight: 400;
        font-size: 16px;
    }		
    .side-content {
        flex: 1;
        float: left;
        z-index: 5;
        background-color: #f9f9f9;
    }
    .side-content .top-bar {
        width: 100%;
        height: 60px;
        -webkit-box-shadow: 9px -5px 13px 2px rgba(0,0,0,0.75);
        -moz-box-shadow: 9px -5px 13px 2px rgba(0,0,0,0.75);
        box-shadow: 9px -5px 13px 2px rgba(0,0,0,0.75);
        background-color: #f9f9f9;
    }
    .side-content .top-bar .container {
        margin-left: 48px;
        display: block;
    }
    .side-content .top-bar .container .menu {
        float: left;
    }
    .side-content .top-bar .container .menu .link {
        margin-right: 80px;
        line-height: 60px;
        float: left;
    }
    .side-content .top-bar .container .menu .link a {
        font-size: 18px;
        text-decoration: none;
        color: #333333;
        font-weight: 600;
    }
    .side-content .top-bar .container .menu .link.active a {
        color: #3c9e95;
    }
    .side-content .top-bar .container .menu .link a:after {
        content: "";
        position: absolute;
        display: inline-block;
        width: 57px;
        height: 57px;
        border-top-right-radius: 5px;
        -webkit-transform: scale(0.707) rotate(45deg);
        transform: scale(0.707) rotate(45deg);
        -webkit-box-shadow: 1px -1px rgba(0, 0, 0, 0.25);
        box-shadow: 1px -1px rgba(0, 0, 0, 0.25);
        z-index: 1;
    }
    .side-content .content {
        width: 100%;
        margin-top: 22px;
    }
    .side-content .content .container {
        margin-left: 48px;
        margin-right: 48px;
        display: block;
    }
    .side-content .content .container .title {
        font-size: 42px;
        font-weight: 100;
        color: #446894;
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .side-content .content .container .subtitle {
        font-size: 22px;
        font-weight: 400;
        color: #446894;
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .side-content .content .container .text {
        font-size: 16px;
        font-weight: 400;
        color: #333333;
        margin-top: 10px;
        line-height: 28px;
        margin-bottom: 10px;
        padding: 14px;
    }
    .side-content .content .container .notes {
        font-size: 16px;
        font-weight: 400;
        color: #f9f9f9;
        margin-top: 10px;
        line-height: 28px;
        margin-bottom: 10px;
        background-color: #446894;
        padding: 18px;
        border-radius: 4px;
    }
    .side-content .content .container .table {
        display: table;
        line-height: 28px;
        width: 100%;
    }
    .side-content .content .container .row-header {
        font-size: 16px;
        color: #333333;
        font-weight: 600;
    }
    .side-content .content .container .row-header, .side-content .content .container .row-content {
        display: table-row;
    }
    .side-content .content .container .cell {
        display: table-cell;
        border-bottom: 1px dotted #333333;
        padding: 6px;
    }
    .side-content .content .container .xml, .side-content .content .container .json {
        padding: 14px;
        overflow: auto;
        width: 65vw;
        font-size: 12px;
        margin: 12px 0px 12px 0px;
        color: #f9f9f9;
        border-radius: 4px;
        background-color: #446894;
    }
    .side-content .content .container .fieldset {
        display: flex;
        border: 2px solid #3c9e95;
        border-radius: 2px;
        line-height: 34px;
        margin-top: 12px;
        margin-bottom: 12px;
    }
    .side-content .content .container .fieldset .name {
        min-width: 56px;
        padding: 2px 12px 2px 12px;
        text-align: center;
        background-color: #3c9e95;
        color: #f9f9f9;
    }
    .side-content .content .container .fieldset .data {
        padding: 2px 12px 2px 12px;
        width: 100%;
    }
    .fieldset .data .label-info {
        color: #446894;
    }
    .fieldset .data .label-important {
        color: #3c9e95;
        font-weight: bold;
    }
    @media screen and (max-width: 720px) {
        .page {
            display: block;
        }
        .side-menu {
            width: 100%;
            min-height: auto;
        }
        .side-content {
            float: none;
        }
        .side-content .top-bar {
            display: none;
        }
        .side-content .content .container .fieldset .data {
            overflow: auto;
            flex: 6;
        }
        .side-content .content .container .fieldset .name {
            box-sizing: content-box;
            width: 100%;
            flex: 2;
        }
        .hidden-large-screen {
            display: inherit;
        }
        .hidden-short-screen {
            display: none;
        }
    }
</style>