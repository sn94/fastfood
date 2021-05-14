<style>
    #search-report-downloader .btn {
        display: flex;
        flex-direction: row;
        height: 30px;
    }

    #search {
        height: 25px !important;
        padding: 1px !important;
        margin: 0px !important;
        background-color: white !important;
        width: 100%;
    }

    #search:focus {
        border-radius: 20px;
        border: #cace82 1px solid;
    }

    #search::placeholder {

        font-size: 14px;
        font-weight: 600;
        color: black;
        font-family: mainfont;
        text-align: center;
    }
</style>

<div id="search-report-downloader" class="d-flex flex-column mb-1"  >

    @if( isset( $top_panel))
    <div id="search-report-downloader-top-panel">

        {{$top_panel}}
    </div>

    @endif
    <div  class="d-flex flex-row" >

        <div id="search-report-downloader-center-panel" class="d-flex flex-row flex-wrap w-100 justify-content-start" >
            {{$slot}}
        </div>
        <div style="display: flex;flex-direction: row;  justify-content: flex-end;align-items: flex-end;">

            <a onclick="dataSearcher.formatoPdf(event)" class="btn btn-sm btn-warning" href="#"><img src="{{url('assets/icons/download.png')}}" />PDF</a>
            <a onclick="dataSearcher.formatoExcel(event);" class="btn btn-sm btn-warning" href="#"><img src="{{url('assets/icons/download.png')}}" />EXCEL</a>
        </div>
    </div>

    @if( $showSearcherInput == "S")
    <input class="form-control form-control-sm" autocomplete="off" id="search" type="text" placeholder="{{$placeholder}}" oninput="{{$callback}}">
    @endif
</div>