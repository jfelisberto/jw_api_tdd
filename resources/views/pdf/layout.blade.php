<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    {{-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $data->filetile }}</title>

    <style>
        @page {
            margin: 10px !important;
            padding: 0 !important;
        }

        body {
            font-size: 1rem;
            font-family: Helvetica, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .content {
            background: #ffffff;
            width: 100%;
            height: auto;
            margin: auto;
        }

        .page-break {
            page-break-after: always;
        }

        .tbl-page-break {
            border-bottom: 1px solid rgb(0, 0, 0);
            page-break-after: always;
        }

        .pagenum:before {
            content: counter(page);
        }

        .table {
            --bs-table-color-type: initial;
            --bs-table-bg-type: initial;
            --bs-table-color-state: initial;
            --bs-table-bg-state: initial;
            --bs-table-color: var(--bs-emphasis-color);
            --bs-table-bg: var(--bs-body-bg);
            --bs-table-border-color: var(--bs-border-color);
            --bs-table-accent-bg: transparent;
            --bs-table-striped-color: var(--bs-emphasis-color);
            --bs-table-striped-bg: rgba(var(--bs-emphasis-color-rgb), 0.05);
            --bs-table-active-color: var(--bs-emphasis-color);
            --bs-table-active-bg: rgba(var(--bs-emphasis-color-rgb), 0.1);
            --bs-table-hover-color: var(--bs-emphasis-color);
            --bs-table-hover-bg: rgba(var(--bs-emphasis-color-rgb), 0.075);
            width: 100%;
            margin-bottom: 1rem;
            vertical-align: top;
        }

        table {
            display: table;
            text-indent: initial;
            border-spacing: 2px;
            caption-side: bottom;
        }

        .table>thead {
            vertical-align: bottom;
        }

        thead {
            display: table-header-group;
            vertical-align: middle;
        }

        .table>tbody {
            vertical-align: inherit;
        }

        tbody {
            display: table-row-group;
            vertical-align: middle;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
        }

        .table>:not(caption)>*>* {
            padding: 0.5rem 0.5rem;
            color: var(--bs-table-color-state,var(--bs-table-color-type,var(--bs-table-color)));
            background-color: var(--bs-table-bg);
            box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state,var(--bs-table-bg-type,var(--bs-table-accent-bg)));
        }

        td {
            display: table-cell;
            vertical-align: inherit;
        }

        .tdcol5 {
            width: 5%
        }

        .tdcol7 {
            width: 7%
        }

        .tdcol9 {
            width: 9%
        }

        .tdcol10 {
            width: 10%
        }

        .tdcol15 {
            width: 15%
        }

        .tdcol20 {
            width: 20%
        }

        .tdcol30 {
            width: 30%;
        }

        .tdcol35 {
            width: 35%;
        }

        .tdcol40 {
            width: 40%;
        }

        .tdcol46 {
            width: 46%;
        }

        .tdcol2 {
            width: 38%;
        }

        .tdcol3 {
            width: 12%;
        }

        .tdh {
            text-align: center;
            text-transform: uppercase;
            vertical-align: middle !important;
        }

        .tdhped {
            font-size: 0.75rem;
        }

        .head2 {
            border-top: 1px solid rgb(0, 0, 0);
            border-left: 1px solid rgb(0, 0, 0);
            border-right: 1px solid rgb(0, 0, 0);
            border-bottom: 1px solid rgb(0, 0, 0);
            font-size: 0.9rem;
        }

        .head2 div {
            margin-top: 0.15rem;
            margin-bottom: 0.15rem;
        }

        .items {
            font-size: 0.8rem;
            border-top: 1px solid rgb(0, 0, 0);
            border-left: 1px solid rgb(0, 0, 0);
            border-right: 1px solid rgb(0, 0, 0);
            border-bottom: 1px solid rgb(0, 0, 0);
        }

        .text-danger {
            --bs-text-opacity: 1;
            color: #ff0000 !important;
        }

        .text-warning {
            --bs-text-opacity: 1;
            color: #ffc107 !important;
        }

        .text-left {
            text-align: left !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .foo1 {
            font-size: 0.8rem;
            border-left: 1px solid rgb(0, 0, 0);
            border-right: 1px solid rgb(0, 0, 0);
            border-bottom: 1px solid rgb(0, 0, 0);
        }

        .foo2 {
            font-size: 0.7rem;
            /* border: 1px solid rgb(0, 0, 0); */
            border-left: 1px solid rgb(0, 0, 0);
            border-right: 1px solid rgb(0, 0, 0);
            border-bottom: 1px solid rgb(0, 0, 0);
        }

        .foo-price {
            font-size: 0.9rem;
        }

        .assign {
            text-align: left;
            padding: 0.5rem;
            margin-top: 1rem;
            display: flex;
        }

        .assign-data {
            vertical-align: middle;
            width: 20%;
            margin-right: 5rem;
        }

        .assign-signature {
            vertical-align: middle;
            width: 20%;
            border-top: 1px solid #000;
            text-align: center;
            padding: 0.3rem;
            margin-right: 5rem;
        }
    </style>
</head>
<body>
    <div class="content">
        @yield('content')
    </div>
</body>
</html>
