@extends("layouts.layout")
@section("title", "profile")
@section("style")
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
    .top-cluster {
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

    .bottom-cluster{
        color: rgba(0,0,0,0.8);
        cursor: pointer;
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

    /* .myprofile-table-row-td2__div-modificable, 
    .myprofile-table-row-td2__div-gender, 
    .stardust-radio-group, 
    .stardust-radio 
    .stardust-radio--checked,
    .stardust-radio-button ,
    .stardust-radio-button--checked */
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
</style>
@endsection
@section("content")
<div class="profile-page-container-outer">
    <div class="profile-page-container">
        <div class="profile-page-main">
            <div class="left-container">
                <div class="top-cluster">
                    <a class="top-left-cluster" href="/profile">
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
                        <div class="top-right-cluster__username">USERNAME</div>
                        <div style="color: rgba(0,0,0,0.8); display: block; font-size: 14px; height: 16.8px; width: 115px; unicode-bidi: isolate; line-height: 16.8px; text-size-adjust: 100%">
                            <a class="pen-icon-modifiy-profile" href="/profile">
                                <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg" style="margin-right: 4px;">
                                    <path d="M8.54 0L6.987 1.56l3.46 3.48L12 3.48M0 8.52l.073 3.428L3.46 12l6.21-6.18-3.46-3.48" fill="#9B9B9B" fill-rule="evenodd"></path>                              
                                </svg>
                                Sửa hồ sơ
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bottom-cluster">
                    <div class="stardust-dropdown">
                        <div class="stardust-dropdown__item-header">
                            <a class="stardust-dropdown__item" href="/user/purchase">
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
                            <a class="stardust-dropdown__item" href="/user/purchase">
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
                            <a class="stardust-dropdown__item" href="/user/purchase">
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
                            <a class="stardust-dropdown__item" href="/user/purchase">
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
                                    <form style="color: rgba(0, 0, 0, 0.8); display: block; font-size: 14px; line-height: 16.8px; margin-top: 0px; text-size-adjust: 100%; unicode-bidi: isolate; width: 502px; height: 457px;">
                                        <table class="myprofile-table">
                                            <tbody>
                                                <tr class="myprofile-table-row" id="myprofile-table-row1">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row1td1">
                                                        <label class="myprofile-table-row-td1__label">Tên đăng nhập</label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row1td2">
                                                        <div class="myprofile-table-row-td2__div" style="height: 64px;">
                                                            <div class="myprofile-table-row-td2__div-content">
                                                                <input type="text" placeholder="" class="profile-username-input" value="wvp00anf7n" data-listener-added_f77ca63a="true">
                                                            </div>
                                                            <div class="myprofile-table-row-td2__div-note">Tên Đăng nhập chỉ có thể thay đổi một lần.</div>
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
                                                                <input type="text" placeholder="" class="profile-name-input" value="Công Phan">
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
                                                            <div class="profile-email-show" style="width: 300px;">ph*************@gmail.com</div>
                                                            <button class="profile-attribute-modify-button">Thay đổi</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row" id="myprofile-table-row4">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row4td1">
                                                        <label class="myprofile-table-row-td1__label">Số điện thoại</label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row4td2">
                                                        <div class="myprofile-table-row-td2__div-modificable">
                                                            <div class="profile-phonenumber-show" style="width: 300px;">*********75</div>
                                                            <button class="profile-attribute-modify-button">Thay đổi</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row" id="myprofile-table-row5">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row5td1">
                                                        <label class="myprofile-table-row-td1__label">Giới tính</label>
                                                    </td>
                                                    <!-- <td class="myprofile-table-row-td2" id="myprofile-table-row5td2">
                                                        <div class="myprofile-table-row-td2__div-gender" style="height: 18 px;">
                                                            <div class="stardust-radio-group" role="radiogroup" style="display: flex;">
                                                                <div class="stardust-radio stardust-radio--checked" tabindex="0" role="radio" aria-checked="true" style="width: 55.5625px; margin-right: 12px; pointer: cursor; font-weight: 400; display: flex; color: rgba(0, 0, 0, 0.87);">
                                                                    <div class="stardust-radio-button stardust-radio-button--checked" style="color: rgba(0, 0, 0, 0.87); pointer: cursor; flex-shrink: 0;
                                                                    font-weight: 400; margin-right: 8px; position: relative; width: 18px;">
                                                                        <div class="stardust-radio-button__outer-circle">
                                                                            <div class="stardust-radio-button__inner-circle"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="stardust-radio__content" style="height: 18px; width: 29.5625px;">
                                                                        <div class="stardust-radio__label" style="height: 16.8px; width: 29.5625px;">Nam</div>
                                                                    </div>
                                                                </div>
                                                                <div class="stardust-radio" tabindex="0" role="radio" aria-checked="false">
                                                                    <div class="stardust-radio-button">
                                                                        <div class="stardust-radio-button__outer-circle">
                                                                            <div class="stardust-radio-button__inner-circle"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="stardust-radio__content" style="height: 18px; width: 19.4875px;">
                                                                        <div class="stardust-radio__label" style="height: 16.8px; width: 19.4875px;">Nữ</div>
                                                                    </div>
                                                                </div>
                                                                <div class="stardust-radio" tabindex="0" role="radio" aria-checked="false">
                                                                    <div class="stardust-radio-button">
                                                                        <div class="stardust-radio-button__outer-circle">
                                                                            <div class="stardust-radio-button__inner-circle"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="stardust-radio__content" style="height: 18px; width: 31.9125px;">
                                                                        <div class="stardust-radio__label" style="height: 16.8px; width: 31.9125px;">Khác</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td> -->
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row5td2">
                                                        <div class="myprofile-table-row-td2__div__gender-ratio" style="height: 18 px;">
                                                            <label class="profile-gender-radio__label">
                                                                <input type="radio" name="gender" value="Nam" class="profile-gender-radio__input"> Nam
                                                            </label>
                                                            <label class="profile-gender-radio__label">
                                                                <input type="radio" name="gender" value="Nữ" class="profile-gender-radio__input"> Nữ
                                                            </label>
                                                            <label class="profile-gender-radio__label">
                                                                <input type="radio" name="gender" value="Khác" class="profile-gender-radio__input"> Khác
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
                                                            <div class="profile-birthday-show" style="width: 300px;">**/08/20**</div>
                                                            <button class="profile-attribute-modify-button">Thay đổi</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- <tr class="myprofile-table-row" id="myprofile-table-row7">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row7td1">
                                                        <label class="myprofile-table-row-td1__label"></label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row7td2">
                                                        <button type="button" class="profile-save-button" aria-disabled="false">Lưu</button>
                                                    </td>
                                                </tr> -->
                                                <tr class="myprofile-table-row__row7">
                                                    <td>
                                                        <label></label>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="profile-save-button">Lưu</button>
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
                                        <!-- <input class="profile-avatar-upload-file-input" type="file" accept=".jpg,.jpeg,.png">
                                        <button type="button" class="profile-avatar-choose-image-button">Chọn ảnh</button> -->
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
@endsection
<!-- <div class="container">
    <div class="profile-container">
        <h2>Quản lý hồ sơ cá nhân</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

            <div class="form-group">
                <label for="name">Họ và tên</label>
                <input type="text" id="name" name="name" class="form-control" 
                       value="{{ $profile->name ?? '' }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="{{ $profile->email ?? '' }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" id="phone" name="phone" class="form-control" 
                       value="{{ $profile->phone ?? '' }}">
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <textarea id="address" name="address" class="form-control">{{ $profile->address ?? '' }}</textarea>
            </div>

            <button type="submit" class="btn-submit">Cập nhật hồ sơ</button>
    </div>
</div> -->

<!-- <div class="utB99K">
    <div class="SFztPl">
        <h1 class="BVrKV_">Hồ sơ của tôi</h1>
        <div class="QcW5xy">Quản lý thông tin hồ sơ để bảo mật tài khoản</div>
    </div>
    <div class="RCnc9v">
        <div class="HrBg9Q">
            <form>
                <table class="bQkdAY">
                    <tr>
                        <td class="f1ZOv_ F4ruY9"><label>Tên đăng nhập</label></td>
                        <td class="o6L4e0">
                            <div>
                                <div class="NGoa5Z">
                                    <input type="text" placeholder="" class="kKnR04" value="wvp00anf7n" data-listener-added_f77ca63a="true">
                                </div>
                                <div class="JQaxZl">Tên Đăng nhập chỉ có thể thay đổi một lần.</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="f1ZOv_"><label>Tên</label></td>
                        <td class="o6L4e0">
                            <div>
                                <div class="NGoa5Z">
                                    <input type="text" placeholder="" class="kKnR04" value="Công Phan">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="f1ZOv_"><label>Email</label></td>
                        <td class="o6L4e0">
                            <div class="e_Vt__">
                                <div class="PBfYlq">ph*************@gmail.com</div>
                                <button class="clo49Q">Thay đổi</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="f1ZOv_"><label>Số điện thoại</label></td>
                        <td class="o6L4e0">
                            <div class="e_Vt__">
                                <div class="PBfYlq">*********75</div>
                                <button class="clo49Q">Thay đổi</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="f1ZOv_"><label>Giới tính</label></td>
                        <td class="o6L4e0">
                            <div class="prDHtK">
                                <div class="stardust-radio-group" role="radiogroup">
                                    <div class="stardust-radio stardust-radio--checked" tabindex="0" role="radio" aria-checked="true">
                                        <div class="stardust-radio-button stardust-radio-button--checked">
                                            <div class="stardust-radio-button__outer-circle">
                                                <div class="stardust-radio-button__inner-circle"></div>
                                            </div>
                                        </div>
                                        <div class="stardust-radio__content">
                                            <div class="stardust-radio__label">Nam</div>
                                        </div>
                                    </div>
                                    <div class="stardust-radio" tabindex="0" role="radio" aria-checked="false">
                                        <div class="stardust-radio-button">
                                            <div class="stardust-radio-button__outer-circle">
                                                <div class="stardust-radio-button__inner-circle"></div>
                                            </div>
                                        </div>
                                        <div class="stardust-radio__content">
                                            <div class="stardust-radio__label">Nữ</div>
                                        </div>
                                    </div>
                                    <div class="stardust-radio" tabindex="0" role="radio" aria-checked="false">
                                        <div class="stardust-radio-button">
                                            <div class="stardust-radio-button__outer-circle">
                                                <div class="stardust-radio-button__inner-circle"></div>
                                            </div>
                                        </div>
                                        <div class="stardust-radio__content">
                                            <div class="stardust-radio__label">Khác</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="f1ZOv_"><label>Ngày sinh</label></td>
                        <td class="o6L4e0">
                            <div class="e_Vt__">
                                <div class="PBfYlq">**/08/20**</div>
                                <button class="clo49Q">Thay đổi</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="f1ZOv_"><label></label></td>
                        <td class="o6L4e0">
                            <button type="button" class="btn btn-solid-primary btn--m btn--inline" aria-disabled="false">Lưu</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="nv7bOz">
            <div class="TJWfNh">
                <div class="nMPYiw" role="header">
                    <div class="gQ6nuc">
                        <svg enable-background="new 0 0 15 15" viewBox="0 0 15 15" x="0" y="0" class="shopee-svg-icon BJDAci icon-headshot">
                            <g>
                                <circle cx="7.5" cy="4.5" fill="none" r="3.8" stroke-miterlimit="10"></circle>
                                <path d="m1.5 14.2c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke-linecap="round" stroke-miterlimit="10"></path>
                            </g>
                        </svg>
                    </div>
                </div>
                <input class="XbWdh7" type="file" accept=".jpg,.jpeg,.png">
                <button type="button" class="btn btn-light btn--m btn--inline">Chọn ảnh</button>
                <div class="T_8sqN">
                    <div class="JIExfq">Dung lượng file tối đa 1 MB</div>
                    <div class="JIExfq">Định dạng: .JPEG, .PNG</div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<!-- <!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ Sơ Shopee</title>
</head>
<body>
    <div class="kr8eST">
        <div class="_9auf1">
            <div class="container BtZOqO">
                <div class="epUsgf">
                    <div class="u6SDuY">
                        <a class="w37kB7" href="/user/account/profile">
                            <div class="shopee-avatar">
                                <div class="shopee-avatar__placeholder">
                                    <svg enable-background="new 0 0 15 15" viewBox="0 0 15 15" x="0" y="0" class="shopee-svg-icon icon-headshot">
                                        <g>
                                            <circle cx="7.5" cy="4.5" fill="none" r="3.8" stroke-miterlimit="10"></circle>
                                            <path d="m1.5 14.2c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke-linecap="round" stroke-miterlimit="10"></path>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        
                        <div class="vDMlrj">
                            <div class="HtUK6o">wvp00anf7n</div>
                            <div>
                                <a class="Kytn1s" href="/user/account/profile">
                                    <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg" style="margin-right: 4px;">
                                        <path d="M8.54 0L6.987 1.56l3.46 3.48L12 3.48M0 8.52l.073 3.428L3.46 12l6.21-6.18-3.46-3.48" fill="#9B9B9B" fill-rule="evenodd"></path>
                                    </svg>
                                    Sửa hồ sơ
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="RCnc9v">
                        <div class="HrBg9Q">
                            <form>
                                <table class="bQkdAY">
                                    <tr>
                                        <td class="f1ZOv_ F4ruY9">
                                            <label>Tên đăng nhập</label>
                                        </td>
                                        <td class="o6L4e0">
                                            <div>
                                                <div class="NGoa5Z">
                                                    <input type="text" placeholder="" class="kKnR04" value="wvp00anf7n">
                                                </div>
                                                <div class="JQaxZl">
                                                    Tên Đăng nhập chỉ có thể thay đổi một lần.
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="f1ZOv_">
                                            <label>Tên</label>
                                        </td>
                                        <td class="o6L4e0">
                                            <div>
                                                <div class="NGoa5Z">
                                                    <input type="text" placeholder="" class="kKnR04" value="Công Phan">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="f1ZOv_">
                                            <label>Email</label>
                                        </td>
                                        <td class="o6L4e0">
                                            <div class="e_Vt__">
                                                <div class="PBfYlq">ph*************@gmail.com</div>
                                                <button class="clo49Q">Thay đổi</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="f1ZOv_">
                                            <label>Số điện thoại</label>
                                        </td>
                                        <td class="o6L4e0">
                                            <div class="e_Vt__">
                                                <div class="PBfYlq">*********75</div>
                                                <button class="clo49Q">Thay đổi</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="f1ZOv_">
                                            <label>Giới tính</label>
                                        </td>
                                        <td class="o6L4e0">
                                            <div class="prDHtK">
                                                <div class="stardust-radio-group" role="radiogroup">
                                                    <div class="stardust-radio stardust-radio--checked" tabindex="0" role="radio" aria-checked="true">
                                                        <div class="stardust-radio-button stardust-radio-button--checked">
                                                            <div class="stardust-radio-button__outer-circle">
                                                                <div class="stardust-radio-button__inner-circle"></div>
                                                            </div>
                                                        </div>
                                                        <div class="stardust-radio__content">
                                                            <div class="stardust-radio__label">Nam</div>
                                                        </div>
                                                    </div>
                                                    <div class="stardust-radio" tabindex="0" role="radio" aria-checked="false">
                                                        <div class="stardust-radio-button">
                                                            <div class="stardust-radio-button__outer-circle">
                                                                <div class="stardust-radio-button__inner-circle"></div>
                                                            </div>
                                                        </div>
                                                        <div class="stardust-radio__content">
                                                            <div class="stardust-radio__label">Nữ</div>
                                                        </div>
                                                    </div>
                                                    <div class="stardust-radio" tabindex="0" role="radio" aria-checked="false">
                                                        <div class="stardust-radio-button">
                                                            <div class="stardust-radio-button__outer-circle">
                                                                <div class="stardust-radio-button__inner-circle"></div>
                                                            </div>
                                                        </div>
                                                        <div class="stardust-radio__content">
                                                            <div class="stardust-radio__label">Khác</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="f1ZOv_">
                                            <label>Ngày sinh</label>
                                        </td>
                                        <td class="o6L4e0">
                                            <div class="e_Vt__">
                                                <div class="PBfYlq">**/08/20**</div>
                                                <button class="clo49Q">Thay đổi</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="f1ZOv_">
                                            <label></label>
                                        </td>
                                        <td class="o6L4e0">
                                            <button type="button" class="btn btn-solid-primary btn--m btn--inline" aria-disabled="false">Lưu</button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> -->

<!-- .profile-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
.profile-container h2 {
    margin-bottom: 20px;
    text-align: center;
    color: #343a40;
}
.form-group label {
    font-weight: bold;
}
.form-group input, .form-group textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ced4da;
    border-radius: 5px;
}
.btn-submit {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}
.btn-submit:hover {
    background-color: #0056b3;
}
.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
} -->

<!-- <div class="stardust-dropdown">
                    <div class="stardust-dropdown__item-header">
                        <a class="jHbobZ" href="/user/account/profile">
                            <div class="U7dHrp">
                                <img src="https://down-vn.img.susercontent.com/file/ba61750a46794d8847c3f463c5e71cc4">
                            </div>
                            <div class="mY8KSl">
                                <span class="fnmbfn">Tài khoản của tôi</span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="stardust-dropdown">
                    <div class="stardust-dropdown__item-header">
                        <a class="jHbobZ" href="/user/purchase">
                            <div class="U7dHrp">
                                <img src="https://down-vn.img.susercontent.com/file/f0049e9df4e536bc3e7f140d071e9078">
                            </div>
                            <div class="mY8KSl">
                                <span class="fnmbfn">Đổi mật khẩu</span>
                            </div>
                        </a>
                    </div>
                    <div class="stardust-dropdown__item-body" style="opacity: 0;">
                        <div class="hGOWVP"></div>
                        </div>
                    </div>
                    <div class="stardust-dropdown">
                        <div class="stardust-dropdown__item-header">
                            <a class="jHbobZ" href="/user/notifications/order">
                                <div class="U7dHrp">
                                    <img src="https://down-vn.img.susercontent.com/file/e10a43b53ec8605f4829da5618e0717c">
                                </div>
                                <div class="mY8KSl">
                                    <span class="fnmbfn">Đơn Mua</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="stardust-dropdown">
                        <div class="stardust-dropdown__item-header">
                            <a class="jHbobZ" href="/user/voucher-wallet">
                                <div class="U7dHrp">
                                    <img src="https://down-vn.img.susercontent.com/file/84feaa363ce325071c0a66d3c9a88748">
                                </div>
                                <div class="mY8KSl">
                                    <span class="fnmbfn">Đổi trả hàng</span>
                                </div>
                            </a>
                        </div>
                        <div class="stardust-dropdown__item-body">
                            <div class="hGOWVP"></div>
                        </div>
                    </div>
                </div>
            </div> -->
<!--
    .stardust-radio-button__outer-circle{
        /* Căn chỉnh và bố cục */
        display: flex;                    /* Sử dụng Flexbox để căn chỉnh */
        align-items: center;              /* Căn chỉnh phần tử con theo chiều dọc */
        justify-content: center;          /* Căn chỉnh phần tử con theo chiều ngang */

        /* Viền (Border) */
        border-collapse: collapse;        /* Xóa khoảng cách giữa các đường viền trong bảng */
        border-top-color: rgb(238, 77, 45);
        border-top-style: solid;          /* Kiểu đường viền trên */
        border-top-width: 1.6px;          /* Độ rộng đường viền trên */
        border-top-left-radius: 100%;     /* Bo tròn góc trên trái */
        border-top-right-radius: 100%;    /* Bo tròn góc trên phải */

        border-right-color: rgb(238, 77, 45);
        border-right-style: solid;        /* Kiểu đường viền bên phải */
        border-right-width: 1.6px;        /* Độ rộng đường viền bên phải */

        border-bottom-color: rgb(238, 77, 45);
        border-bottom-style: solid;       /* Kiểu đường viền dưới */
        border-bottom-width: 1.6px;       /* Độ rộng đường viền dưới */
        border-bottom-left-radius: 100%;  /* Bo tròn góc dưới trái */
        border-bottom-right-radius: 100%; /* Bo tròn góc dưới phải */

        border-left-color: rgb(238, 77, 45);
        border-left-style: solid;         /* Kiểu đường viền bên trái */
        border-left-width: 1.6px;         /* Độ rộng đường viền bên trái */

        /* Các thuộc tính khác về viền */
        border-image-outset: 0;           /* Không có khoảng cách ngoài đường viền */
        border-image-repeat: stretch;     /* Kéo dài đường viền nếu có hình ảnh */
        border-image-slice: 100%;         /* Cắt toàn bộ hình ảnh đường viền */
        border-image-source: none;        /* Không có hình ảnh đường viền */
        border-image-width: 1;            /* Độ rộng của hình ảnh đường viền */

        /* Các thuộc tính về kích thước và vị trí */
        width: 18px;                      /* Chiều rộng của phần tử */
        height: 18px;                     /* Chiều cao của phần tử */
        box-sizing: border-box;           /* Bao gồm cả padding và border vào trong kích thước tổng */

        /* Màu sắc */
        color: rgba(0, 0, 0, 0.87);       /* Màu sắc văn bản */
        cursor: pointer;                  /* Con trỏ chuột chuyển thành tay khi di chuột qua */

        /* Font chữ */
        font-family: -apple-system, "Helvetica Neue", Helvetica, Roboto, "Droid Sans", Arial, sans-serif;
        font-size: 14px;                   /* Kích thước font */
        font-weight: 400;                  /* Độ dày font bình thường */
        line-height: 16.8px;               /* Chiều cao dòng */

        /* Các thuộc tính về văn bản */
        text-indent: 0px;                  /* Không thụt lề văn bản */
        text-size-adjust: 100%;            /* Điều chỉnh kích thước văn bản cho các thiết bị */
        unicode-bidi: isolate;            /* Chế độ xử lý văn bản từ trái sang phải */

        /* Khoảng cách */
        -webkit-border-horizontal-spacing: 0px;  /* Khoảng cách đường viền ngang cho trình duyệt WebKit */
        -webkit-border-vertical-spacing: 0px;    /* Khoảng cách đường viền dọc cho trình duyệt WebKit */
    }

    .stardust-radio-button__inner-circle{
        /* Cấu hình nền (Background) */
        background-attachment: scroll;              /* Nền di chuyển khi cuộn trang */
        background-clip: border-box;                /* Nền nằm trong phạm vi của border-box */
        background-color: rgb(238, 77, 45);         /* Màu nền */
        background-image: none;                     /* Không có hình nền */
        background-origin: padding-box;             /* Nền bắt đầu từ vùng padding */
        background-position-x: 0%;                  /* Vị trí của nền theo chiều ngang */
        background-position-y: 0%;                  /* Vị trí của nền theo chiều dọc */
        background-repeat: repeat;                  /* Lặp lại nền */
        background-size: auto;                      /* Kích thước nền tự động */

        /* Viền (Border) */
        border-bottom-left-radius: 100%;            /* Bo tròn góc dưới trái */
        border-bottom-right-radius: 100%;           /* Bo tròn góc dưới phải */
        border-top-left-radius: 100%;               /* Bo tròn góc trên trái */
        border-top-right-radius: 100%;              /* Bo tròn góc trên phải */
        border-collapse: collapse;                  /* Xóa khoảng cách giữa các đường viền trong bảng */

        /* Màu sắc và con trỏ */
        color: rgba(0, 0, 0, 0.87);                 /* Màu sắc văn bản */
        cursor: pointer;                            /* Con trỏ chuột chuyển thành tay khi di chuột qua */

        /* Bố cục và hiển thị */
        display: block;                             /* Hiển thị phần tử dưới dạng block */
        height: 6px;                                /* Chiều cao của phần tử */
        width: 6px;                                 /* Chiều rộng của phần tử */
        opacity: 1;                                 /* Độ mờ của phần tử */
        line-height: 16.8px;                        /* Chiều cao dòng */
        text-indent: 0px;                           /* Không thụt lề văn bản */

        /* Font chữ */
        font-family: -apple-system, "Helvetica Neue", Helvetica, Roboto, "Droid Sans", Arial, sans-serif;
        font-size: 14px;                            /* Kích thước font */
        font-weight: 400;                           /* Độ dày font bình thường */

        /* Văn bản và điều chỉnh */
        text-size-adjust: 100%;                      /* Điều chỉnh kích thước văn bản cho các thiết bị */
        unicode-bidi: isolate;                      /* Chế độ xử lý văn bản từ trái sang phải */

        /* Khoảng cách (Chỉ cho trình duyệt WebKit) */
        -webkit-border-horizontal-spacing: 0px;     /* Khoảng cách viền ngang */
        -webkit-border-vertical-spacing: 0px;       /* Khoảng cách viền dọc */
    }

    .stardust-radio__content, .stardust-radio__label{
        /* Cấu hình bảng */
        border-collapse: collapse;                  /* Xóa khoảng cách giữa các đường viền trong bảng */

        /* Màu sắc và con trỏ */
        color: rgba(0, 0, 0, 0.87);                 /* Màu văn bản */
        cursor: pointer;                            /* Con trỏ chuột khi di chuột qua */

        /* Bố cục và hiển thị */
        display: block;                             /* Hiển thị phần tử dưới dạng block */

        /* Font chữ */
        font-family: -apple-system, "Helvetica Neue", Helvetica, Roboto, "Droid Sans", Arial, sans-serif; /* Cài đặt font chữ */
        font-size: 14px;                            /* Kích thước font */
        font-weight: 400;                           /* Độ dày font (bình thường) */
        line-height: 16.8px;                        /* Chiều cao dòng */

        /* Văn bản và điều chỉnh */
        text-indent: 0px;                           /* Không thụt lề văn bản */
        text-size-adjust: 100%;                      /* Điều chỉnh kích thước văn bản cho các thiết bị */

        /* Xử lý văn bản */
        unicode-bidi: isolate;                      /* Chế độ xử lý văn bản từ trái sang phải */

        /* Khoảng cách (Chỉ cho trình duyệt WebKit) */
        -webkit-border-horizontal-spacing: 0px;     /* Khoảng cách viền ngang */
        -webkit-border-vertical-spacing: 0px;       /* Khoảng cách viền dọc */
    }  -->