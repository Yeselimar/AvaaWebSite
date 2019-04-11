<!-- Editor -->
<style>

    .dropup, .dropdown {
        position: relative;
    }

    .btn-group, .btn-group-vertical {
        position: relative;
        display: inline-block;
        vertical-align: middle;
    }

    .btn-group > .btn:first-child:not(:last-child):not(.dropdown-toggle) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .btn-group > .btn:first-child {
        margin-left: 0;
    }
    .btn-default {
        color: #333 !important;
        background-color: #fff !important;
        border-color: #ccc !important;
    }
    .btn-default:hover {
        color: #333 !important;
        background-color: #e6e6e6 !important;
        border-color: #adadad !important;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 160px;
        padding: 5px 0;
        margin: 2px 0 0;
        font-size: 14px;
        text-align: left;
        list-style: none;
        background-color: #fff;
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
        border: 1px solid #ccc;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: 4px;
        -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
    }
    .btn-group > .btn, .btn-group-vertical > .btn {
        position: relative;
        float: left;
    }

    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .btn-group .btn + .btn, .btn-group .btn + .btn-group, .btn-group .btn-group + .btn, .btn-group .btn-group + .btn-group {
        margin-left: -1px;
    }


</style>
<link rel="stylesheet" href="{{asset('public_sisbeca/css/lib/html5-editor/bootstrap3-wysihtml5.css')}}" />