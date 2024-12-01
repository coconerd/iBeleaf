@extends("layouts.layout")
@section("title", "Profile")
@section("style")

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .profile-page-container-outer{
        display: flex;
        justify-content: center;
        background-color: #FFFAFA;
    }

    .profile-page-container {
        color: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        font-family: "Helvetica Neue", Helvetica, Arial, 文泉驛正黑, "WenQuanYi Zen Hei", 
                    "Hiragino Sans GB", "儷黑 Pro", "LiHei Pro", "Heiti TC", 微軟正黑體, 
                    "Microsoft JhengHei UI", "Microsoft JhengHei", sans-serif;
        font-size: 14px;
        height: 615.6px;
        line-height: 16.8px;
        text-size-adjust: 100%;
        transition-behavior: normal;
        transition-delay: 0s;
        transition-duration: 0.3s;
        transition-property: margin-top;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        unicode-bidi: isolate;
        width: 1240px;
    }

    .profile-page-main {
        color: rgba(0, 0, 0, 0.8);
        display: flex;
        font-family: "Helvetica Neue", Helvetica, Arial, 文泉驛正黑, "WenQuanYi Zen Hei", 
                    "Hiragino Sans GB", "儷黑 Pro", "LiHei Pro", "Heiti TC", 微軟正黑體, 
                    "Microsoft JhengHei UI", "Microsoft JhengHei", sans-serif;
        font-size: 14px;
        height: 615.6px;
        width: 1024px;
        line-height: 16.8px;
        margin-left: 108px;
        margin-right: 108px;
        max-width: 1024px;
        padding-bottom: 20px;
        padding-left: 0px;
        padding-right: 0px;
        padding-top: 20px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
    }

    .left-container {
        color: rgba(0, 0, 0, 0.8);
        display: block;
        flex-shrink: 0;
        font-family: "Helvetica Neue", Helvetica, Arial, 文泉驛正黑, "WenQuanYi Zen Hei", 
                    "Hiragino Sans GB", "儷黑 Pro", "LiHei Pro", "Heiti TC", 微軟正黑體, 
                    "Microsoft JhengHei UI", "Microsoft JhengHei", sans-serif;
        font-size: 14px;
        height: 565px;
        line-height: 16.8px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 180px;
    }
    .left-container__top-cluster {
        font-size: 14px;
        width: 180px;
        height: 50px;
        line-height: 16.8px;
        padding: 15px 0 60px 0;
        display: flex;
        color: rgba(0, 0, 0, 0.8);
        border-bottom: 0.8px solid rgb(239, 239, 239);
        text-size-adjust: 100%;
        unicode-bidi: isolate;
    }

    .top-left-cluster{
        background-color: rgba(0,0,0,0);
        color: rgb(85,26,139);
        cursor: pointer;
        display: block;
        font-size: 14px;
        width: 50px;
        height: 50px;
        line-height: 16.8px;
        text-decoration-color: rgb(0,0,238);
        text-size-adjust: 100%;
        text-decoration-line: none;
        text-decoration-style: solid;
        text-decoration-thickness: auto;
    }

    .svg-icon-headshot{
        color: rgb(85,26,139);
        cursor: pointer;
        display: block;
        fill: rgb(0,0,238);
        font-size: 24px;
        font-weight: 400;
        height: 24px;
        left: 24.2px;
        line-height: 32px;
        overflow-clip-margin: content-box;
        overflow-x: hidden;
        overflow-y: hidden;
        position: absolute;
        stroke: rgb(198,198,198);
        text-size-adjust: 100%;
        top: 24.2px;
        transform: matrix(1,0,0,1,-12,-12);
        width: 24px;
        x: 0px;
        y: 0px;
        -webkit-font-smoothing: antialiased;
    }

    .group-svg {
        color: rgb(85,26,139);
        cursor: pointer;
        display: inline;
        fill: rgb(0,0,238);
        font-size: 24px;
        font-weight: 400;
        height: auto;
        line-height: 32px;
        stroke: rgb(198,198,198);
        text-size-adjust: 100%;
        transform-orgin: 0px 0px;
        width: auto;
        -webkit-font-smoothing: antialiased;
    }

    .avatar__placeholder{
        background-color: rgb(245,245,245);
        border-bottom-left-radius: 50%;
        border-bottom-right-radius: 50%;
        border-top-left-radius: 50%;
        border-top-right-radius: 50%;
        color: rgb(85,26,139);
        cursor: pointer;
        display: block;
        font-size: 14px;
        height: 0px;
        line-height: 16.8px;
        overflow-x: hidden;
        overflow-y: hidden;
        padding-top: 48.4px;
        position: relative;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 48.4px;
    }

    .avatar{
        border-bottom-color: rgba(0,0,0,0.09);
        border-bottom-left-radius: 50%;
        border-bottom-right-radius: 50%;
        border-bottom-style: solid;
        border-bottom-width: 0.8px;
        border-image-outset: 0;
        border-image-repeat: stretch;
        border-image-slice: 100%;
        border-image-source: none;
        border-image-width: 1;
        border-left-color: rgba(0,0,0,0.09);
        border-left-style: solid;
        border-left-width: 0.8px;
        border-right-color: rgba(0,0,0,0.09);
        border-right-style: solid;
        border-right-width: 0.8px;
        border-top-color: rgba(0,0,0,0.09);
        border-top-left-radius: 50%;
        border-top-right-radius: 50%;
        border-top-style: solid;
        border-top-width: 0.8px;
        box-sizing: border-box;
        color: rgb(85,26,139);
        cursor: pointer;
        display: inline-block;
        font-size: 14px;
        height: 50px;
        line-height: 16.8px;
        position: relative;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 50px;
    }

    .top-right-cluster{
        color: rgba(0,0,0,0.8);
        flex-basis: 0%;
        flex-direction: column;
        flex-grow: 1;
        flex-shrink: 1;
        display: flex;
        font-size: 14px;
        height: 50px;
        justify-content: center;
        overflow: hidden;
        padding-left: 15px;
        line-height: 16.8px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 115px;
    }

    .top-right-cluster__username{
        color: rgb(51,51,51);
        display: block;
        font-size: 14px;
        font-weight: 600;
        height: 16.8px;
        width: 115px;
        line-height: 16.8px;
        min-height: 16px;
        margin-bottom: 5px;
        overflow: hidden;
        text-overflow: ellipsis;
        text-size-adjust: 100%;
        text-wrap-mode: nowrap;
        unicode-bidi: isolate;
        white-space-collapse: collapse;
    }

    .pen-icon-modifiy-profile{
        background-color: rgba(0,0,0,0);
        color: rgb(136,136,136);
        cursor: pointer;
        display: inline;
        font-size: 14px;
        height: auto;
        width: auto;
        line-height: 16.8px;
        text-decoration-color: rgb(136,136,136);
        text-decoration-line: none;
        text-decoration-style: solid;
        text-decoration-thickness: auto;
        text-size-adjust: 100%;
    }

    .left-container__bottom-cluster{
        color: rgba(0,0,0,0.8);
        /* cursor: pointer; */
        display: block;
        font-size: 14px;
        height: 225px;
        width: 180px;
        line-height: 16.8px;
        list-style-image: none;
        list-style-position: outside;
        list-style-type: none;
        margin-bottom: 0px;
        margin-left: 0px;
        margin-right: 0px;
        margin-top: 27px;
        padding-bottom: 0px;
        padding-left: 0px;
        padding-right: 0px;
        padding-top: 0px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
    }

    .stardust-dropdown{
        color: rgba(0,0,0,0.8);
        cursor: pointer;
        display: block;
        font-size: 14px;
        height: 35px;
        line-height: 16.8px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 180px;
        position: relative;
        list-style-type: none;
        list-style-position: outside;
        list-style-image: none;
    }

    .stardust-dropdown__item-header{
        color: rgba(0,0,0,0.8);
        cursor: pointer;
        display: block;
        font-size: 14px;
        height: 20px;
        line-height: 16.8px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 180px;
        list-style-type: none;
        list-style-position: outside;
        list-style-image: none;
    }

    .stardust-dropdown__item-body{
        color: rgba(0,0,0,0.8);
        cursor: pointer;
        display: block;
        font-size: 14px;
        height: 0px;
        line-height: 16.8px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 180px;
        list-style-type: none;
        list-style-position: outside;
        list-style-image: none;
        overflow: hidden;
        transition-property: height;
        transition-duration: 0.4s, 0.4s;
        transition-timing-function: cubic-bezier(0.4,0,0.2,1), cubic-bezier(0.4,0,0.2,1);
        transition-delay: 0s, 0s;
        transition-behavior: normal, normal;
        transition-property: height, opacity;
        opacity: 0;
    }

    .stardust-dropdown__item{
        color: rgba(0,0,0,0.87);
        align-items: center;
        background-color: rgba(0,0,0,0);
        cursor: pointer;
        display: flex;
        font-size: 14px;
        height: 20px;
        line-height: 16.8px;
        text-size-adjust: 100%;
        width: 180px;
        list-style-type: none;
        list-style-position: outside;
        list-style-image: none;
        margin-bottom: 15px;
        text-decoration-color: rgba(0,0,0,0.87);
        text-decoration-line: none;
        text-decoration-style: solid;
        text-decoration-thickness: auto;
        text-transform: capitalize;
        transition-property: background-color;
        transition-duration: 0.1s;
        transition-timing-function: cubic-bezier(0.4,0,0.2,1);
        transition-delay: 0s;
        transition-behavior: normal;
    }

    .stardust-dropdown__item-icon{
        align-items: center;
        border-bottom-left-radius: 50%;
        border-bottom-right-radius: 50%;
        border-top-left-radius: 50%;
        border-top-right-radius: 50%;
        color: rgb(255,255,255);
        cursor: pointer;
        display: flex;
        flex-shrink: 0;
        font-size: 14px;
        height: 20px;
        justify-content: center;
        line-height: 20px;
        line-style-image: none;
        line-style-position: outside;
        line-style-type: none;
        margin-right: 10px;
        text-align: center;
        text-size-adjust: 100%;
        text-transform: capitalize;
        unicode-bidi: isolate;
        width: 20px;
    }

    .stardust-dropdown__item-text{
        color: rgba(0,0,0,0.87);
        cursor: pointer;
        display: block;
        font-size: 14px;
        line-height: 16px;
        height: 16px;
        width: 100px;
        list-style-type: none;
        list-style-position: outside;
        list-style-image: none;
        text-transform: capitalize;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
    }

    .stardust-dropdown__item-icon-img{
        border-bottom-color: rgb(255, 255, 255);
        border-bottom-style: none;
        border-bottom-width: 0px;
        border-image-outset: 0;
        border-image-repeat: stretch;
        border-image-slice: 100%;
        border-image-source: none;
        border-image-width: 1;
        border-left-color: rgb(255, 255, 255);
        border-left-style: none;
        border-left-width: 0px;
        border-right-color: rgb(255, 255, 255);
        border-right-style: none;
        border-right-width: 0px;
        border-top-color: rgb(255, 255, 255);
        border-top-style: none;
        border-top-width: 0px;
        color: rgb(255, 255, 255);
        cursor: pointer;
        display: block;
        height: 20px;
        width: 20px;
        overflow-clip-margin: content-box;
        overflow-x: clip;
        overflow-y: clip;
    }

    .stardust-dropdown__item-text-span{
        color: rgba(0,0,0,0.87);
        cursor: pointer;
        display: inline;
        font-size: 14px;
        line-height: 16px;
        height: auto;
        width: auto;
        font-weight: 500;
        list-style-type: none;
        list-style-position: outside;
        list-style-image: none;
        text-transform: capitalize;
        text-size-adjust: 100%;
        margin-right: 5px;
    }

    .stardust-dropdown__item-text-span:hover {
        color: #3B54DF; /* Thay đổi màu chữ */
        font-weight: bold; /* Làm chữ đậm */
        background-color: transparent; /* Giữ nguyên nền */
    }

    .stardust-dropdown__item-body-padding{
        color: rgba(0,0,0,0.8);
        cursor: pointer;
        display: block;
        font-size: 14px;
        height: 0px;
        line-height: 16.8px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 146px;
        list-style-type: none;
        list-style-position: outside;
        list-style-image: none;
        padding-bottom: 3px;
        padding-left: 34px;
        padding-right: 0px;
        padding-top: 0px;
    }

    .right-container{
        background-attachment: scroll;
        background-clip: border-box;
        background-color: rgb(255, 255, 255);
        background-image: none;
        background-origin: padding-box;
        background-position-x: 0%;
        background-position-y: 0%;
        background-repeat: repeat;
        background-size: auto;
        border-bottom-left-radius: 2px;
        border-bottom-right-radius: 2px;
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        box-shadow: rgba(0, 0, 0, 0.13) 0px 1px 2px 0px;
        box-sizing: border-box;
        color: rgba(0, 0, 0, 0.8);
        display: block;
        flex-grow: 1;
        font-family: "Helvetica Neue", Helvetica, Arial, 文泉驛正黑, "WenQuanYi Zen Hei", 
                    "Hiragino Sans GB", "儷黑 Pro", "LiHei Pro", "Heiti TC", 微軟正黑體, 
                    "Microsoft JhengHei UI", "Microsoft JhengHei", sans-serif;
        font-size: 14px;
        height: 565px;
        width: 900px;
        line-height: 16.8px;
        margin-left: 27px;
        min-width: 0px;
        position: relative;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
    }

    .right-container-main{
        color: rgba(0, 0, 0, 0.8);
        display: flex;
        flex-direction: column;
        font-family: "Helvetica Neue", Helvetica, Arial, 文泉驛正黑, "WenQuanYi Zen Hei", 
                    "Hiragino Sans GB", "儷黑 Pro", "LiHei Pro", "Heiti TC", 微軟正黑體, 
                    "Microsoft JhengHei UI", "Microsoft JhengHei", sans-serif;
        font-size: 14px;
        height: 565px;
        width: 817px;
        line-height: 16.8px;
        min-height: 100%;
        position: relative;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
    }

    .right-container-main-inner{
        color: rgba(0, 0, 0, 0.8);
        display: block;
        font-size: 14px;
        height: 565px;
        width: 900px;
        line-height: 16.8px;
        /* padding-bottom: 10px; */
        padding-left: 30px;
        padding-right: 30px;
        padding-top: 0px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
    }

    .right-container-main-inner__top{
        border-bottom-color: rgb(239, 239, 239);
        border-bottom-style: solid;
        border-bottom-width: 0.8px;
        color: rgba(0, 0, 0, 0.8);
        display: block;
        font-size: 14px;
        height: 44px;
        line-height: 16.8px;
        padding-bottom: 60px;
        padding-left: 0px;
        padding-right: 0px;
        padding-top: 18px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 757px;
    }

    .right-container-main-inner__bottom{
        align-items: flex-start;
        color: rgba(0, 0, 0, 0.8);
        display: flex;
        flex-direction: row;
        font-size: 14px;
        height: 487px;
        width: 757px;
        line-height: 16.8px;
        padding-top: 30px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
    }

    .right-container-main-inner__bottom__left-cluster{
        color: rgba(0, 0, 0, 0.8);
        display: block;
        flex-basis: 0%;
        flex-grow: 1;
        flex-shrink: 1;
        font-size: 14px;
        height: 457px;
        width: 552px;
        line-height: 16.8px;
        padding-right: 50px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
    }

    .myprofile-table{
        border-bottom-color: rgb(128, 128, 128);
        border-left-color: rgb(128, 128, 128);
        border-right-color: rgb(128, 128, 128);
        border-top-color: rgb(128, 128, 128);
        margin: 0;
        padding: 0;
        border-collapse: collapse;
        border-spacing: 0;
        box-sizing: border-box;
        color: rgba(0, 0, 0, 0.8);
        font-size: 14px;
        height: 457px;
        width: 407.8px;
        line-height: 16.8px;
        text-indent: 0px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        -webkit-border-horizontal-spacing: 0px;
        -webkit-border-vertical-spacing: 0px;
        table-layout: fixed;
    }

    .myprofile-table-row{
        border-bottom-color: rgb(128, 128, 128);
        border-collapse: collapse;
        border-left-color: rgb(128, 128, 128);
        border-right-color: rgb(128, 128, 128);
        border-top-color: rgb(128, 128, 128);
        color: rgba(0, 0, 0, 0.8);
        display: table-row;
        font-size: 14px;
        line-height: 16.8px;
        text-indent: 0px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        vertical-align: middle;
        width: 502px;
        -webkit-border-horizontal-spacing: 0px;
        -webkit-border-vertical-spacing: 0px;
    }

    .myprofile-table-row__row7{
        border-bottom-color: rgb(128, 128, 128);
        border-collapse: collapse;
        border-left-color: rgb(128, 128, 128);
        border-right-color: rgb(128, 128, 128);
        border-top-color: rgb(128, 128, 128);
        color: rgba(0, 0, 0, 0.8);
        display: flex;
        font-size: 14px;
        line-height: 16.8px;
        text-indent: 0px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        vertical-align: middle;
        width: 502px;
        height: 70px;
        align-items: center;
    }

    .myprofile-table-row__row7 td:nth-child(2) {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    #myprofile-table-row1 {
        height: 94px;
    }
    #myprofile-table-row2, #myprofile-table-row7 {
        height: 70px;
    }
    #myprofile-table-row3, #myprofile-table-row4, #myprofile-table-row6 {
        height: 47.6px;
    }
    #myprofile-table-row5 {
        height: 48px;
    }

    .myprofile-table-row-td1{
        border-collapse: collapse;
        color: rgba(85, 85, 85, 0.8);
        display: table-cell;
        font-size: 14px;
        line-height: 16.8px;
        min-width: 20%;
        overflow-x: hidden;
        overflow-y: hidden;
        padding-left: 0px;
        padding-right: 0px;
        padding-top: 0px;
        text-align: right;
        text-indent: 0px;
        text-size-adjust: 100%;
        text-wrap-mode: nowrap;
        unicode-bidi: isolate;
        vertical-align: middle;
        white-space-collapse: collapse;
        width: 95.425px;
        -webkit-border-horizontal-spacing: 0px;
        -webkit-border-vertical-spacing: 0px;
    }
    #myprofile-table-row1td1 {
        padding-bottom: 54px;
        height: 40px;
    }
    #myprofile-table-row2td1, #myprofile-table-row7td1 {
        padding-bottom: 30px;
        height: 40px;
    }
    #myprofile-table-row3td1, #myprofile-table-row4td1, #myprofile-table-row6td1 {
        padding-bottom: 30px;
        height: 17.6px;
    }
    #myprofile-table-row5td1 {
        padding-bottom: 30px;
        height: 18px;
    }

    .myprofile-table-row-td2{
        border-collapse: collapse;
        box-sizing: border-box;
        color: rgba(0, 0, 0, 0.8);
        display: table-cell;
        font-size: 14px;
        line-height: 16.8px;
        min-width: 500px;
        padding-bottom: 30px;
        padding-left: 20px;
        padding-right: 0px;
        padding-top: 0px;
        text-indent: 0px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        vertical-align: middle;
        width: 407.8px;
        -webkit-border-horizontal-spacing: 0px;
        -webkit-border-vertical-spacing: 0px;
    }

    #myprofile-table-row1td2 {
        height: 94px;
    }
    #myprofile-table-row2td2{
        height: 70px;
    }
    #myprofile-table-row3td2, #myprofile-table-row4td2, #myprofile-table-row6td2 {
        height: 47.6px;
    }
    #myprofile-table-row5td2 {
        height: 48px;
    }
    #myprofile-table-row7td2 {
        height: 70px;
        text-align: center;
    }

    .myprofile-table-row-td1__label{
        border-collapse: collapse;
        color: rgba(85, 85, 85, 0.8);
        cursor: default;
        display: inline;
        font-size: 14px;
        height: auto;
        line-height: 16.8px;
        text-align: right;
        text-indent: 0px;
        text-size-adjust: 100%;
        text-wrap-mode: nowrap;
        white-space-collapse: collapse;
        width: auto;
        -webkit-border-horizontal-spacing: 0px;
        -webkit-border-vertical-spacing: 0px;
    }

    .myprofile-table-row-td2__div{
        border-collapse: collapse;
        color: rgba(0, 0, 0, 0.8);
        display: block;
        font-size: 14px;
        line-height: 16.8px;
        text-indent: 0px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 387.8px;
        -webkit-border-horizontal-spacing: 0px;
        -webkit-border-vertical-spacing: 0px;
    }

    .myprofile-table-row-td2__div-content{
        align-items: center;
        border-bottom-color: rgba(0, 0, 0, 0.14);
        border-bottom-left-radius: 2px;
        border-bottom-right-radius: 2px;
        border-bottom-style: solid;
        border-bottom-width: 0.8px;
        border-collapse: collapse;
        border-image-outset: 0;
        border-image-repeat: stretch;
        border-image-slice: 100%;
        border-image-source: none;
        border-image-width: 1;
        border-left-color: rgba(0, 0, 0, 0.14);
        border-left-style: solid;
        border-left-width: 0.8px;
        border-right-color: rgba(0, 0, 0, 0.14);
        border-right-style: solid;
        border-right-width: 0.8px;
        border-top-color: rgba(0, 0, 0, 0.14);
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        border-top-style: solid;
        border-top-width: 0.8px;
        box-shadow: rgba(0, 0, 0, 0.02) 0px 2px 0px 0px inset;
        box-sizing: border-box;
        color: rgba(0, 0, 0, 0.8);
        display: flex;
        font-size: 14px;
        height: 40px;
        width: 387.8px;
        line-height: 16.8px;
        overflow-x: hidden;
        overflow-y: hidden;
        text-indent: 0px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        -webkit-border-horizontal-spacing: 0px;
        -webkit-border-vertical-spacing: 0px;
    }

    .profile-username-input, .profile-name-input{
        appearance: auto;
        background-attachment: scroll;
        background-clip: border-box;
        background-color: rgba(0, 0, 0, 0);
        background-image: none;
        background-origin: padding-box;
        background-position-x: 0%;
        background-position-y: 0%;
        background-repeat: repeat;
        background-size: auto;

        border-bottom-color: rgba(0, 0, 0, 0.8);
        border-bottom-style: none;
        border-bottom-width: 0px;

        border-collapse: collapse;

        border-image-outset: 0;
        border-image-repeat: stretch;
        border-image-slice: 100%;
        border-image-source: none;
        border-image-width: 1;

        border-left-color: rgba(0, 0, 0, 0.8);
        border-left-style: none;
        border-left-width: 0px;

        border-right-color: rgba(0, 0, 0, 0.8);
        border-right-style: none;
        border-right-width: 0px;

        border-top-color: rgba(0, 0, 0, 0.8);
        border-top-style: none;
        border-top-width: 0px;

        color: rgba(0, 0, 0, 0.8);
        cursor: text;
        display: block;

        filter: none;

        flex-basis: 0%;
        flex-grow: 1;
        flex-shrink: 0;

        font-family: "Helvetica Neue", Helvetica, Arial, 文泉驛正黑, "WenQuanYi Zen Hei", 
                    "Hiragino Sans GB", "儷黑 Pro", "LiHei Pro", "Heiti TC", 微軟正黑體, 
                    "Microsoft JhengHei UI", "Microsoft JhengHei", sans-serif;

        font-feature-settings: normal;
        font-kerning: auto;
        font-optical-sizing: auto;
        font-size: 14px;
        font-size-adjust: none;
        font-stretch: 100%;
        font-style: normal;
        font-variant-alternates: normal;
        font-variant-caps: normal;
        font-variant-east-asian: normal;
        font-variant-emoji: normal;
        font-variant-ligatures: normal;
        font-variant-numeric: normal;
        font-variant-position: normal;
        font-variation-settings: normal;
        font-weight: 400;

        height: 16.8px;

        letter-spacing: normal;
        line-height: normal;

        margin-bottom: 0px;
        margin-left: 0px;
        margin-right: 0px;
        margin-top: 0px;

        outline-color: rgba(0, 0, 0, 0.8);
        outline-style: none;
        outline-width: 0px;

        overflow-clip-margin: 0px;
        overflow-x: clip;
        overflow-y: clip;

        padding-block-end: 12px;
        padding-block-start: 12px;
        padding-bottom: 12px;
        padding-inline-end: 12px;
        padding-inline-start: 12px;
        padding-left: 12px;
        padding-right: 12px;
        padding-top: 12px;

        text-align: start;
        text-indent: 0px;
        text-rendering: auto;
        text-shadow: none;
        text-size-adjust: 100%;
        text-transform: none;

        width: 363.8px;

        word-break: break-all;
        word-spacing: 0px;

        -webkit-border-horizontal-spacing: 0px;
        -webkit-border-vertical-spacing: 0px;
        -webkit-rtl-ordering: logical;
        -webkit-border-image: none;
    }

    .myprofile-table-row-td2__div-note{
        border-collapse: collapse;
        color: rgba(0, 0, 0, 0.4);
        display: block;

        font-family: "Helvetica Neue", Helvetica, Arial, 文泉驛正黑, "WenQuanYi Zen Hei", 
                    "Hiragino Sans GB", "儷黑 Pro", "LiHei Pro", "Heiti TC", 微軟正黑體, 
                    "Microsoft JhengHei UI", "Microsoft JhengHei", sans-serif;
        font-size: 14px;

        height: 20px;
        line-height: 20px;

        margin-bottom: 0px;
        margin-left: 0px;
        margin-right: 0px;
        margin-top: 0px;

        padding-bottom: 0px;
        padding-left: 0px;
        padding-right: 0px;
        padding-top: 4px;

        text-indent: 0px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;

        width: 387.8px;

        -webkit-border-horizontal-spacing: 0px;
        -webkit-border-vertical-spacing: 0px;
    }

    .myprofile-table-row-td2__div-modificable{
        border-collapse: collapse;
        color: rgba(0, 0, 0, 0.8);
        display: flex;
        font-size: 14px;
        line-height: 16.8px;
        text-indent: 0px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 387.8px;
        -webkit-border-horizontal-spacing: 0px;
        -webkit-border-vertical-spacing: 0px;
    }

    .myprofile-table-row-td2__div__gender-ratio{
        border-collapse: collapse;
        color: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 14px;
        line-height: 16.8px;
        text-indent: 0px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 387.8px;
    }

    .profile-gender-radio__input{
        margin-right: 5px;
    }

    .profile-gender-radio__label{
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .profile-email-show, .profile-phonenumber-show, .profile-birthday-show{
        border-collapse: collapse;
        color: rgb(51, 51, 51);
        display: block;
        font-family: "Helvetica Neue", Helvetica, Arial, 文泉驛正黑, "WenQuanYi Zen Hei", "Hiragino Sans GB", "儷黑 Pro", "LiHei Pro", "Heiti TC", 微軟正黑體, "Microsoft JhengHei UI", "Microsoft JhengHei", sans-serif;
        font-size: 14px;
        height: 16.8px;
        line-height: 16.8px;
        text-indent: 0px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        -webkit-border-horizontal-spacing: 0px;
        -webkit-border-vertical-spacing: 0px;
    }

    .profile-attribute-modify-button{
        align-items: flex-start;
        appearance: button;
        background-attachment: scroll;
        background-clip: border-box;
        background-color: rgba(0, 0, 0, 0);
        background-image: none;
        background-origin: padding-box;
        background-position-x: 0%;
        background-position-y: 0%;
        background-repeat: repeat;
        background-size: auto;
        border-bottom-color: rgb(0, 85, 170);
        border-bottom-style: none;
        border-bottom-width: 0px;
        border-collapse: collapse;
        border-image-outset: 0;
        border-image-repeat: stretch;
        border-image-slice: 100%;
        border-image-source: none;
        border-image-width: 1;
        border-left-color: rgb(0, 85, 170);
        border-left-style: none;
        border-left-width: 0px;
        border-right-color: rgb(0, 85, 170);
        border-right-style: none;
        border-right-width: 0px;
        border-top-color: rgb(0, 85, 170);
        border-top-style: none;
        border-top-width: 0px;
        box-sizing: border-box;
        color: rgb(0, 85, 170);
        cursor: pointer;
        display: block;
        font-family: "Helvetica Neue", Helvetica, Arial, 文泉驛正黑, "WenQuanYi Zen Hei", "Hiragino Sans GB", "儷黑 Pro", "LiHei Pro", "Heiti TC", 微軟正黑體, "Microsoft JhengHei UI", "Microsoft JhengHei", sans-serif;
        font-feature-settings: normal;
        font-kerning: auto;
        font-optical-sizing: auto;
        font-size: 13px;
        font-size-adjust: none;
        font-stretch: 100%;
        font-style: normal;
        font-variant-alternates: normal;
        font-variant-caps: normal;
        font-variant-east-asian: normal;
        font-variant-emoji: normal;
        font-variant-ligatures: normal;
        font-variant-numeric: normal;
        font-variant-position: normal;
        font-variation-settings: normal;
        font-weight: 400;
        height: 17.6px;
        letter-spacing: normal;
        line-height: 15.6px;
        outline-color: rgb(0, 85, 170);
        outline-style: none;
        outline-width: 0px;
        overflow-x: visible;
        overflow-y: visible;
        padding-block-end: 1px;
        padding-block-start: 1px;
        padding-inline-end: 6px;
        padding-inline-start: 6px;
        text-align: center;
        text-decoration-color: rgb(0, 85, 170);
        text-decoration-line: underline;
        text-decoration-style: solid;
        text-decoration-thickness: auto;
        text-indent: 0px;
        text-rendering: auto;
        text-shadow: none;
        text-size-adjust: 100%;
        text-transform: capitalize;
        width: 64.025px;
        word-spacing: 0px;
        -webkit-border-horizontal-spacing: 0px;
        -webkit-border-vertical-spacing: 0px;
        -webkit-border-image: none;
    }

    .profile-attribute-modify-button:hover{
        color: #3B54DF;
        /* font-weight: bold; */
    }
    
    .profile-save-button{
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 70px;
        height: 40px;
        padding: 0px 20px;
        background-color: #48846E;
        background-image: none;
        background-clip: border-box;
        background-origin: padding-box;
        background-size: auto;
        background-repeat: repeat;
        background-position: 0% 0%;
        border: none;
        border-radius: 2.5px;
        border-collapse: collapse;
        box-sizing: border-box;
        font-family: "Helvetica Neue", Helvetica, Arial, 文泉驛正黑, "WenQuanYi Zen Hei", 
                    "Hiragino Sans GB", "儷黑 Pro", "LiHei Pro", "Heiti TC", 微軟正黑體, 
                    "Microsoft JhengHei UI", "Microsoft JhengHei", sans-serif;
        font-size: 14px;
        font-weight: 400;
        line-height: 16.8px;
        color: rgb(255, 255, 255);
        text-align: center;
        text-transform: capitalize;
        text-overflow: ellipsis;
        text-indent: 0px;
        letter-spacing: normal;
        word-spacing: 0px;
        cursor: pointer;
    }

    .profile-save-button:hover{
        background-color: #50845F;
    }

    .right-container-main-inner__bottom__right-cluster{
        border-left-color: rgb(239, 239, 239);
        border-left-style: solid;
        border-left-width: 0.8px;
        color: rgba(0, 0, 0, 0.8);
        display: flex;
        font-size: 14px;
        height: 232px;
        width: 235px;
        justify-content: center;
    }

    .right-container-main-inner__bottom__right-cluster__div{
        align-items: center;
        color: rgba(0, 0, 0, 0.8);
        display: flex;
        flex-direction: column;
        font-family: "Helvetica Neue", Helvetica, Arial, 
                    文泉驛正黑, "WenQuanYi Zen Hei", "Hiragino Sans GB", 
                    "儷黑 Pro", "LiHei Pro", "Heiti TC", 微軟正黑體, 
                    "Microsoft JhengHei UI", "Microsoft JhengHei", sans-serif;
        font-size: 14px;
        height: 232px;
        line-height: 16.8px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 235px;
    }

    .right-container-bottom-right-cluster-show-image__div{
        align-items: center;
        color: rgba(0, 0, 0, 0.8);
        display: flex;
        font-size: 14px;
        height: 100px;
        justify-content: center;
        line-height: 16.8px;
        margin-bottom: 20px;
        margin-left: 0px;
        margin-right: 0px;
        margin-top: 20px;
        position: relative;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 100px;
    }

    .right-container-bottom-right-cluster-show-image__content{
        background-attachment: scroll;
        background-clip: border-box;
        background-color: rgb(239, 239, 239);
        background-image: none;
        background-origin: padding-box;
        background-position-x: 0%;
        background-position-y: 0%;
        background-repeat: repeat;
        background-size: auto;
        border-bottom-left-radius: 50%;
        border-bottom-right-radius: 50%;
        border-top-left-radius: 50%;
        border-top-right-radius: 50%;
        color: rgba(0, 0, 0, 0.8);
        display: block;
        font-size: 14px;
        height: 100px;
        line-height: 16.8px;
        overflow-x: hidden;
        overflow-y: hidden;
        position: relative;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 100px;
    }

    .right-container-bottom-right-cluster-show-image__content-svgicon{
        color: rgba(0, 0, 0, 0.8);
        display: block;
        fill: rgba(0, 0, 0, 0.8);
        font-size: 24px;
        font-weight: 400;
        height: 50px;
        left: 50px;
        line-height: 32px;
        overflow-clip-margin: content-box;
        overflow-x: hidden;
        overflow-y: hidden;
        position: absolute;
        stroke: rgb(198, 198, 198);
        text-size-adjust: 100%;
        top: 50px;
        transform: matrix(1, 0, 0, 1, -25, -25);
        width: 50px;
        x: 0px;
        y: 0px;
        -webkit-font-smoothing: antialiased;
    }

    .right-container-bottom-right-cluster-show-image__content-svg-group{
        color: rgba(0, 0, 0, 0.8);
        display: inline;
        fill: rgba(0, 0, 0, 0.8);
        font-size: 24px;
        font-weight: 400;
        height: auto;
        line-height: 32px;
        stroke: rgb(198, 198, 198);
        text-size-adjust: 100%;
        transform-origin: 0px 0px;
        width: auto;
        -webkit-font-smoothing: antialiased;
    }
    
    .profile-avatar-upload-file-input {
        display: none; /* Ẩn hoàn toàn input gốc */
    }

    .profile-avatar-choose-image-button {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fff;
        color: #555;
        border: 1px solid rgba(0, 0, 0, 0.09);
        border-radius: 2px;
        height: 40px;
        width: 120px;
        min-width: 70px;
        max-width: 220px;
        font-size: 14px;
        text-align: center;
        cursor: pointer;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.03);
        padding: 0 20px;
        outline: none;
    }

    .profile-avatar-choose-image-button:hover {
        background-color: #f9f9f9;
    }

    .right-container-bottom-right-cluster-note{
        color: rgba(0, 0, 0, 0.8);
        display: block;
        font-family: "Helvetica Neue", Helvetica, Arial, 文泉驛正黑, "WenQuanYi Zen Hei", "Hiragino Sans GB", "儷黑 Pro", "LiHei Pro", "Heiti TC", 微軟正黑體, "Microsoft JhengHei UI", "Microsoft JhengHei", sans-serif;
        font-size: 14px;
        height: 40px;
        line-height: 16.8px;
        margin-top: 12px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 175px;
    }
    .right-container-bottom-right-cluster-note__content{
        color: rgb(153, 153, 153);
        display: block;
        font-family: "Helvetica Neue", Helvetica, Arial, 文泉驛正黑, "WenQuanYi Zen Hei", "Hiragino Sans GB", "儷黑 Pro", "LiHei Pro", "Heiti TC", 微軟正黑體, "Microsoft JhengHei UI", "Microsoft JhengHei", sans-serif;
        font-size: 14px;
        height: 20px;
        line-height: 20px;
        text-size-adjust: 100%;
        unicode-bidi: isolate;
        width: 175px;
    }

    /* CSS cho phần hiển thị thông báo */
    .custom-alert-container {
        position: absolute;
        top: 97px; /* nằm ngay phía dưới navbar */
        right: 10px;
        z-index: 1000;
        max-width: 400px;
        margin-top: 20px;
    }

    .alert {
    padding: 15px;
    margin-bottom: 10px;
    border: 1px solid transparent;
    border-radius: 4px;
    font-size: 14px;
    }

    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }

    .alert-info {
        color: #31708f;
        background-color: #d9edf7;
        border-color: #bce8f1;
    }

    .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }
</style>
@endsection

@section("content")
<!-- Phần hiển thị thông báo -->
<div class="custom-alert-container">
    <!-- Phần hiển thị lỗi khi validate form (nếu có) -->
    @if ($errors->any())
        <div class="alert alert-danger" id="validationAlert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- Phần hiển thị thông báo từ session (nếu có) -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
</div>

<!-- Giao diện chính của trang profile -->
<div class="profile-page-container-outer">
    <div class="profile-page-container">
        <div class="profile-page-main">
            <div class="left-container">
                <div class="left-container__top-cluster">
                    <a class="top-left-cluster" href="{{route('profile.homepage')}}">
                        <div class="avatar">
                            <div class="avatar__placeholder">
                                <svg enable-background="new 0 0 15 15" viewBox="0 0 15 15" x="0" y="0" class="svg-icon-headshot">
                                    <g class="group-svg">
                                        <circle cx="7.5" cy="4.5" fill="none" r="3.8" stroke-miterlimit="10"></circle>
                                        <path d="m1.5 14.2c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke-linecap="round" stroke-miterlimit="10"></path>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <div class="top-right-cluster">
                        <div class="top-right-cluster__username">
                            <!-- @if (auth()->check())
                            {{auth()->user()->user_name}}
                            @endif -->
                            @if (auth()->check())
                                {{$user->user_name}}
                            @endif
                        </div>
                        <div style="color: rgba(0,0,0,0.8); display: block; font-size: 14px; height: 16.8px; width: 115px; unicode-bidi: isolate; line-height: 16.8px; text-size-adjust: 100%">
                            <a class="pen-icon-modifiy-profile" href="{{route('profile.homepage')}}">
                                <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg" style="margin-right: 4px;">
                                    <path d="M8.54 0L6.987 1.56l3.46 3.48L12 3.48M0 8.52l.073 3.428L3.46 12l6.21-6.18-3.46-3.48" fill="#9B9B9B" fill-rule="evenodd"></path>                              
                                </svg>
                                Sửa hồ sơ
                            </a>
                        </div>
                    </div>
                </div>

                <div class="left-container__bottom-cluster">
                    <div class="stardust-dropdown">
                        <div class="stardust-dropdown__item-header">
                            <a class="stardust-dropdown__item" href="{{route('profile.homepage')}}">
                                <div class="stardust-dropdown__item-icon">
                                    <img src="{{asset('images/icons8-registration-50.png')}}" class="stardust-dropdown__item-icon-img">
                                </div>
                                <div class="stardust-dropdown__item-text">
                                    <span class="stardust-dropdown__item-text-span">Hồ sơ</span>
                                </div>
                            </a>
                        </div>
                        <div class="stardust-dropdown__item-body" style="opacity: 0;">
                            <div class="stardust-dropdown__item-body-padding"></div>
                        </div>
                    </div>

                    <div class="stardust-dropdown">
                        <div class="stardust-dropdown__item-header">
                            <a class="stardust-dropdown__item" href="{{route('profile.changePassword')}}">
                                <div class="stardust-dropdown__item-icon">
                                    <img src="{{asset('images/icons8-access-50.png')}}" class="stardust-dropdown__item-icon-img">
                                </div>
                                <div class="stardust-dropdown__item-text">
                                    <span class="stardust-dropdown__item-text-span">Đổi mật khẩu</span>
                                </div>
                            </a>
                        </div>
                        <div class="stardust-dropdown__item-body" style="opacity: 0;">
                            <div class="stardust-dropdown__item-body-padding"></div>
                        </div>
                    </div>

                    <div class="stardust-dropdown">
                        <div class="stardust-dropdown__item-header">
                            <a class="stardust-dropdown__item" href="{{route('profile.orders')}}">
                                <div class="stardust-dropdown__item-icon">
                                    <img src="{{asset('images/icons8-order-50.png')}}" class="stardust-dropdown__item-icon-img">
                                </div>
                                <div class="stardust-dropdown__item-text">
                                    <span class="stardust-dropdown__item-text-span">Đơn mua</span>
                                </div>
                            </a>
                        </div>
                        <div class="stardust-dropdown__item-body" style="opacity: 0;">
                            <div class="stardust-dropdown__item-body-padding"></div>
                        </div>
                    </div>
    
                    <div class="stardust-dropdown">
                        <div class="stardust-dropdown__item-header">
                            <a class="stardust-dropdown__item" href="{{route('profile.returns')}}">
                                <div class="stardust-dropdown__item-icon">
                                    <img src="{{asset('images/icons8-return-purchase-50.png')}}" class="stardust-dropdown__item-icon-img">
                                </div>
                                <div class="stardust-dropdown__item-text">
                                    <span class="stardust-dropdown__item-text-span">Đổi trả hàng</span>
                                </div>
                            </a>
                        </div>
                        <div class="stardust-dropdown__item-body" style="opacity: 0;">
                            <div class="stardust-dropdown__item-body-padding"></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="right-container">
                <div class="right-container-main" role="main">
                    <div style="color: rgba(0, 0, 0, 0.8); display: contents; font-size: 14px; height: auto; line-height: 16.8px; text-size-adjust: 100%; unicode-bidi: isolate; width: auto;">
                        <div class="right-container-main-inner">
                            <div class="right-container-main-inner__top">
                                <h1 style="color: rgb(51, 51, 51); display: block; font-size: 20px; font-weight: 500; height: 24px; line-height: 24px; margin-block-end: 0px; margin-block-start: 0px; margin-bottom: 0px; margin-inline-end: 0px; margin-inline-start: 0px; margin-left: 0px; margin-right: 0px; margin-top: 0px; text-size-adjust: 100%; text-transform: capitalize; unicode-bidi: isolate; width: 757px;">Hồ sơ của tôi</h1>
                                <div style="color: rgb(85, 85, 85); display: block; font-size: 14px; height: 17px; line-height: 17px; margin-top: 3px; text-size-adjust: 100%; unicode-bidi: isolate; width: 757px;">Quản lý thông tin hồ sơ để bảo mật tài khoản</div>
                            </div>
                            <div class="right-container-main-inner__bottom">
                                <div class="right-container-main-inner__bottom__left-cluster">
                                    <form method="POST" action="{{ route('profile.update') }}" style="color: rgba(0, 0, 0, 0.8); display: block; font-size: 14px; line-height: 16.8px; margin-top: 0px; text-size-adjust: 100%; unicode-bidi: isolate; width: 502px; height: 457px;">
                                        @csrf
                                        <table class="myprofile-table">
                                            <tbody>
                                                <tr class="myprofile-table-row" id="myprofile-table-row1">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row1td1">
                                                        <label class="myprofile-table-row-td1__label">Tên đăng nhập</label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row1td2">
                                                        <div class="myprofile-table-row-td2__div" style="height: 64px;">
                                                            <div class="myprofile-table-row-td2__div-content">
                                                                <!-- <input type="text" placeholder="" class="profile-username-input" value="{{ auth()->check() ? auth()->user()->user_name : '' }}" > -->
                                                                <input type="text" name="username" placeholder="" class="profile-username-input" value="{{ auth()->check() ? $user->user_name : '' }}" >
                                                            </div>
                                                            <div class="myprofile-table-row-td2__div-note">Tên đăng nhập chỉ có thể thay đổi một lần</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row" id="myprofile-table-row2">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row2td1">
                                                        <label class="myprofile-table-row-td1__label">Tên</label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row2td2">
                                                        <div class="myprofile-table-row-td2__div" style="height: 40px;">
                                                            <div class="myprofile-table-row-td2__div-content">
                                                                <input type="text" name="fullname" placeholder="" class="profile-name-input" value="{{ auth()->check() ? $user->full_name : '' }}">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row" id="myprofile-table-row3">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row3td1">
                                                        <label class="myprofile-table-row-td1__label">Email</label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row3td2">
                                                        <div class="myprofile-table-row-td2__div-modificable">
                                                            <div class="profile-email-show" id="profile-show-email" style="width: 300px;">
                                                                <!-- @if (auth()->check())
                                                                    {{auth()->user()->email}}
                                                                @endif -->
                                                                @if (auth()->check())
                                                                    {{$user->email}}
                                                                @endif
                                                            </div>
                                                            <input type="hidden" name="email" id="hidden-email">
                                                            <!-- data-bs-toggle và data-bs-target dành cho bootstrap 5 -->
                                                            <button type="button" class="profile-attribute-modify-button" data-bs-toggle="modal" data-bs-target="#emailModal">Thay đổi</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row" id="myprofile-table-row4">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row4td1">
                                                        <label class="myprofile-table-row-td1__label">Số điện thoại</label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row4td2">
                                                        <div class="myprofile-table-row-td2__div-modificable">
                                                            <div class="profile-phonenumber-show" id="profile-show-phone" style="width: 300px;">
                                                                <!-- @if (auth()->check())
                                                                    {{auth()->user()->phone_number}}
                                                                @endif -->
                                                                @if (auth()->check())
                                                                    {{$user->phone_number}}
                                                                @endif
                                                            </div>
                                                            <input type="hidden" name="phone" id="hidden-phone">
                                                            <!-- data-bs-toggle và data-bs-target dành cho bootstrap 5 -->
                                                            <button type="button" class="profile-attribute-modify-button" data-bs-toggle="modal" data-bs-target="#phoneModal">Thay đổi</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row" id="myprofile-table-row5">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row5td1">
                                                        <label class="myprofile-table-row-td1__label">Giới tính</label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row5td2">
                                                        <div class="myprofile-table-row-td2__div__gender-ratio" style="height: 18 px;">
                                                            <label class="profile-gender-radio__label">
                                                                <input type="radio" name="gender" value="Nam" class="profile-gender-radio__input"
                                                                    {{ old('gender', auth()->check() ? $user->gender : '') == 'Nam' ? 'checked' : '' }}> Nam
                                                            </label>
                                                            <label class="profile-gender-radio__label">
                                                                <input type="radio" name="gender" value="Nữ" class="profile-gender-radio__input"
                                                                    {{ old('gender', auth()->check() ? $user->gender : '') == 'Nữ' ? 'checked' : '' }}> Nữ
                                                            </label>
                                                            <label class="profile-gender-radio__label">
                                                                <input type="radio" name="gender" value="Khác" class="profile-gender-radio__input"
                                                                    {{ old('gender', auth()->check() ? $user->gender : '') == 'Khác' ? 'checked' : '' }}> Khác
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row" id="myprofile-table-row6">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row6td1">
                                                        <label class="myprofile-table-row-td1__label">Ngày sinh</label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row6td2">
                                                        <div class="myprofile-table-row-td2__div-modificable" style="display: flex; height: 17.6px; align-items: center;">
                                                            <div class="profile-birthday-show" id="profile-show-dob" style="width: 300px;">
                                                                <!-- @if(auth()->check())
                                                                    {{auth()->user()->date_of_birth}}
                                                                @endif -->
                                                                @if (auth()->check())
                                                                    {{ date('Y-m-d', strtotime($user->date_of_birth)) }}
                                                                @endif
                                                            </div>
                                                            <input type="hidden" name="dob" id="hidden-dob">
                                                            <!-- data-bs-toggle và data-bs-target dành cho bootstrap 5 -->
                                                            <button type="button" class="profile-attribute-modify-button" data-bs-toggle="modal" data-bs-target="#dobModal">Thay đổi</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row__row7">
                                                    <td>
                                                        <label></label>
                                                    </td>
                                                    <td>
                                                        <button type="submit" class="profile-save-button">Lưu</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                                <div class="right-container-main-inner__bottom__right-cluster">
                                    <div class="right-container-main-inner__bottom__right-cluster__div">
                                        <div class="right-container-bottom-right-cluster-show-image__div" role="header">
                                            <div class="right-container-bottom-right-cluster-show-image__content">
                                                <svg enable-background="new 0 0 15 15" viewBox="0 0 15 15" x="0" y="0" class="right-container-bottom-right-cluster-show-image__content-svgicon">
                                                    <g class="right-container-bottom-right-cluster-show-image__content-svg-group">
                                                        <circle cx="7.5" cy="4.5" fill="none" r="3.8" stroke-miterlimit="10"></circle>
                                                        <path d="m1.5 14.2c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke-linecap="round" stroke-miterlimit="10"></path>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                        <input type="file" class="profile-avatar-upload-file-input" id="avatarUpload"/>
                                        <label for="avatarUpload" class="profile-avatar-choose-image-button">Chọn ảnh</label>
                                        <div class="right-container-bottom-right-cluster-note">
                                            <div class="right-container-bottom-right-cluster-note__content">Dung lượng file tối đa 1 MB</div>
                                            <div class="right-container-bottom-right-cluster-note__content">Định dạng: jpeg, jpg, png</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Phần modal đối với Bootstrap 5 -->
<!-- Modal cho Email -->
<div class="modal fade" id="emailModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Thay đổi Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="emailChangeForm">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="emailInput" name="email" value="{{ $user->email }}">
                        <small class="text-danger" id="emailError" style="display:none;"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="emailSaveBtn">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal cho Số điện thoại -->
<div class="modal fade" id="phoneModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="phoneModalLabel">Thay đổi Số điện thoại</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="phoneChangeForm">
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" class="form-control" id="phoneInput" name="phone" value="{{ $user->phone_number }}">
                        <small class="text-danger" id="phoneError" style="display:none;"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="phoneSaveBtn">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal cho Ngày sinh -->
<div class="modal fade" id="dobModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dobModalLabel">Thay đổi Ngày sinh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="dobChangeForm">
                    <div class="form-group">
                        <label for="date_of_birth">Ngày sinh</label>
                        <input type="date" class="form-control" id="dobInput" name="date_of_birth" value="{{ date('Y-m-d', strtotime($user->date_of_birth)) }}">
                        <small class="text-danger" id="dobError" style="display:none;"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="dobSaveBtn">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<!-- Bootstrap 5 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<!--Import file profile.js từ thư mục public/js -->
<script src="{{ asset('js/profile.js') }}"></script>

@endpush