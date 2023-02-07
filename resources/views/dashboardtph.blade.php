@include('layout/header')
<style>
  table.dataTable thead tr th {
    border: 1px solid black
  }

  .dataTables_scrollBody thead tr[role="row"] {
    visibility: collapse !important;
  }

  .dataTables_scrollHeaderInner table {
    margin-bottom: 0px !important;
  }

  table.tableRekap {
    width: 100%;
    font-size: 14px;
  }

  a {
    color: black;
  }

  a:hover {
    color: rgb(0, 255, 0);
  }
</style>

<div class="content-wrapper">
  <div class="d-flex justify-content-center">
    <div class="row mt-3 text-uppercase">
      <h1>Sidak Pemeriksaan TPH Regional-I</h1>
    </div>
  </div>

  <div class="d-flex justify-content-end head mt-2" id="head">
    <form action="{{route('dashboardtph')}}" method="get" class="d-flex justify-contend-end mr-3">
      {{ csrf_field() }}
      <input type="week" name="dateWeek" id="dateWeek" value="{{ date('Y').'-W'.date('W') }}">
    </form>

    <div class="row mr-2">
      <button id="btnShow" class="btn btn-primary"><i class="bi bi-arrow-counterclockwise"></i>Show</button>
    </div>

    <form action="{{route('exportPDF')}}" method="POST" id="myForm" class="mr-2">
      {{ csrf_field() }}
      <input type="hidden" id="startWeek" name="start" value="">
      <input type="hidden" id="lastWeek" name="last" value="">
      <input type="hidden" name="chartData" id="chartInputData">
      <button type="submit" class="btn btn-primary" id="btnExport" formtarget="_blank"> <i class="fa fa-file-pdf"></i>
        Download PDF</button>
    </form>

    @if (session('user_name') == 'Dennis Irawan')
    <a href="{{ route('listAsisten') }}" class="btn btn-success mr-2">List Asisten</a>
    @endif
  </div>

  {{-- Data untuk Table --}}
  <section class="content mt-3">
    <div class="d-flex justify-content-center text-center border border-secondary border-2"
      style="background-color: #e8ecdc">
      <h3>REKAPITULASI RANKING NILAI SIDAK PEMERIKSAAN TPH</h3>

    </div>

    <div class="row">
      <div class="col-sm-4">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table id="table1" class="tableRekap table table-hover table-bordered table-sm table-light border-dark">

                <thead>
                  <tr>
                    <th colspan="6" class="text-center" style="background-color : yellow;">
                      WILAYAH I
                    </th>
                  </tr>
                  <tr>

                    <th rowspan="2" style="background-color : #1D43A2; color: #FFFFFF;" class="text-center p-3">
                      KEBUN
                    </th>
                    <th rowspan="2" style="background-color : #1D43A2; color: #FFFFFF;" class="text-center p-3">AFD
                    </th>
                    <th rowspan="2" style="background-color : #1D43A2; color: #FFFFFF;" class="text-center p-3">NAMA
                    </th>
                    <th colspan="2" style="background-color : #1D43A2; color: #FFFFFF;" class="text-center">Todate</th>

                  </tr>
                  <tr>
                    <th style="background-color : #1D43A2; color: #FFFFFF;" class="text-center">Score</th>
                    <th style="background-color : #1D43A2; color: #FFFFFF;" class="text-center">Rank</th>
                  </tr>
                </thead>
                <tbody id="tbody1">
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table id="table2" class="tableRekap table table-hover table-bordered table-sm table-light border-dark">

                <thead>
                  <tr>
                    <th colspan="6" class="text-center" style="background-color : yellow;">
                      WILAYAH II
                    </th>
                  </tr>
                  <tr>

                    <th rowspan="2" style="background-color : #1D43A2; color: #FFFFFF;" class="text-center p-3">
                      KEBUN
                    </th>
                    <th rowspan="2" style="background-color : #1D43A2; color: #FFFFFF;" class="text-center p-3">AFD
                    </th>
                    <th rowspan="2" style="background-color : #1D43A2; color: #FFFFFF;" class="text-center p-3">NAMA
                    </th>
                    <th colspan="2" style="background-color : #1D43A2; color: #FFFFFF;" class="text-center">Todate</th>

                  </tr>
                  <tr>
                    <th style="background-color : #1D43A2; color: #FFFFFF;" class="text-center">Score</th>
                    <th style="background-color : #1D43A2; color: #FFFFFF;" class="text-center">Rank</th>
                  </tr>
                </thead>
                <tbody id="tbody2">
                  <tr>
                    {{-- <td>Budi</td>
                    <td>Prakarya</td>
                    <td>Makassar</td>
                    <td>Makassar</td>
                    <td>Makassar</td> --}}
                  </tr>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class=" col-sm-4">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table id="table3" class="tableRekap table table-hover table-bordered table-sm table-light border-dark">

                <thead>
                  <tr>
                    <th colspan="6" class="text-center" style="background-color : yellow;">
                      WILAYAH III
                    </th>
                  </tr>
                  <tr>

                    <th rowspan="2" style="background-color : #1D43A2; color: #FFFFFF;" class="text-center p-3">
                      KEBUN
                    </th>
                    <th rowspan="2" style="background-color : #1D43A2; color: #FFFFFF;" class="text-center p-3">AFD
                    </th>
                    <th rowspan="2" style="background-color : #1D43A2; color: #FFFFFF;" class="text-center p-3">NAMA
                    </th>
                    <th colspan="2" style="background-color : #1D43A2; color: #FFFFFF;" class="text-center">
                      Todate</th>

                  </tr>
                  <tr>
                    <th style="background-color : #1D43A2; color: #FFFFFF;" class="text-center">Score</th>
                    <th style="background-color : #1D43A2; color: #FFFFFF;" class="text-center">Rank</th>
                  </tr>
                </thead>
                <tbody id="tbody3">
                  <tr>

                  </tr>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="card">
      <div class="card-body">
        <table class=" table table-hover table-bordered table-sm table-light border-dark">
          {{-- <thead>
            <tr>
              <th>test</th>
            </tr>
          </thead> --}}
          <tbody class="text-center" id="tbodySkorRH">

          </tbody>
        </table>
      </div>

    </div>
  </section>

  {{-- Data untuk chart --}}
  <section class="content mt-3 pb-4">

    <div class="d-flex justify-content-center text-center border border-secondary border-2"
      style="background-color: #e8ecdc">
      <h3>Grafik Sidak TPH berdasarkan Estate</h3>
    </div>
    {{-- chart bag 1 --}}
    <div class="row" id="chart">
      <div class="card col-sm-6">
        <div class="card-header d-flex card-light justify-content-center">
          <h5>Brondolan Tinggal (Brondol / Blok)</h5>
        </div>
        <div class="card-body pb-5">
          <div id="bttinggal"></div>
        </div>
      </div>
      <div class="card col-sm-6">
        <div class="card-header d-flex card-light justify-content-center">
          <h5>Karung Berisi Brondolan (Karung / Blok)</h5>
        </div>
        <div class="card-body pb-5">
          <div id="karung"></div>
        </div>
      </div>
      <div class="card col-sm-6">
        <div class="card-header d-flex card-light justify-content-center">
          <h5>Buah Tinggal (Janjang / Blok)</h5>
        </div>
        <div class="card-body pb-5">
          <div id="btt_tgl"></div>
        </div>
      </div>
      <div class="card col-sm-6">
        <div class="card-header d-flex card-light justify-content-center">
          <h5>Restan Tidak Dilaporkan (Janjang / Blok)/h5>
        </div>
        <div class="card-body pb-5">
          <div id="rst_none"></div>
        </div>
      </div>
    </div><br>
    <div class="d-flex justify-content-center text-center border border-secondary border-2"
      style="background-color: #e8ecdc">
      <h3>Grafik Sidak TPH berdasarkan Wilayah</h3>
    </div>

    {{-- chart bg 2 --}}
    <div class="row" id="chart">
      <div class="card col-sm-6">
        <div class="card-header d-flex card-light justify-content-center">
          <h5>Brondolan Tinggal DI TPH</h5>
        </div>
        <div class="card-body pb-5">
          <div id="btt_id"></div>
        </div>
      </div>
      <div class="card col-sm-6">
        <div class="card-header d-flex card-light justify-content-center">
          <h5>Karung berisi Brondolan</h5>
        </div>
        <div class="card-body pb-5">
          <div id="karung_id"></div>
        </div>
      </div>
      <div class="card col-sm-6">
        <div class="card-header d-flex card-light justify-content-center">
          <h5>Buah tinggal TPH</h5>
        </div>
        <div class="card-body pb-5">
          <div id="bttTglTph_id"></div>
        </div>
      </div>
      <div class="card col-sm-6">
        <div class="card-header d-flex card-light justify-content-center">
          <h5>Restan Tidak Dilaporkan</h5>
        </div>
        <div class="card-body pb-5">
          <div id="rst_none_id"></div>
        </div>
      </div>
    </div><br>
</div>

</section>
</div>
@include('layout/footer')




<script type="text/javascript">
  $(document).ready(function () {
    var mybutton = document.getElementById("btnExport");
    mybutton.disabled = true;
    setTimeout(function() {
        mybutton.disabled = false;
    }, 1500);

    ///membuat temp data value 0 untuk chart ketika ganti tanggal tidak ada data
    var list_estate = <?php echo json_encode($list_estate); ?>;
    var list_wilayah = <?php echo json_encode($list_wilayah); ?>;
    // console.log(list_wilayah)

    var estate = '['
    list_estate.forEach(element => {
            estate += '"' +element + '",'
          });
    estate = estate.substring(0, estate.length - 1);
    estate += ']'

    var wilayah = '['
    list_wilayah.forEach(element => {
            wilayah += '"' +element + '",'
          });
    wilayah = wilayah.substring(0, wilayah.length - 1);
    wilayah += ']'
    // console.log(wilayah)     

    //buat grafik temporary untuk value 0 untuk estate
    var estateJson = JSON.parse(estate)
    // console.log(estateJson)     
        var options = {
          
           series: [
    	{ name: '',
       data: [0, 0 , 0, 0, 0, 0, 0 , 0 ,0 , 0 , 0 , 0] }
         ],
            chart: {
              background: '#ffffff',
            height: 350,
            type: 'bar'
            },
            plotOptions: {
    bar: {
        distributed: true
    }
  },
  colors: [
  '#00FF00',
  '#00FF00',
  '#00FF00',
  '#00FF00',
   '#3063EC', 
   '#3063EC', 
   '#3063EC', 
   '#3063EC', 
   '#FF8D1A', 
   '#FF8D1A', 
   '#FF8D1A', 
   '#FF8D1A'
  ],
  //  '#3063EC', '#3063EC',  '#3063EC', '#3063EC',
  //  'FF8D1A', 'FF8D1A', 'FF8D1A', 'FF8D1A'],
   // warna merah, hijau, biru, kuning, hijau muda, ungu, abu-abu, maroon
            // colors:['#1565c0', '#b71c1c', '#9C27B0'],
            stroke: {
            curve: 'smooth'
            },
            xaxis: {
            labels: {
              rotate: -50,
              rotateAlways: true
            },
            type: '',
            categories: estateJson
        }
    };
    //buat grafik dengan value 0 dengan wilayah
    var willJson = JSON.parse(wilayah)

var will = {
    series: [{
    name: '',
    data: [0, 0 , 0]
    }],
    chart: {
    height: 250,
    background: '#E2EAEA',
    type: 'bar'
    },
    plotOptions: {
    bar: {
      // horizontal: false
      distributed: true
    }
  },
    colors:['#E6F011', '#0F0F0E', '#0068A3'],
    stroke: {
    curve: 'smooth'
    },
    xaxis: {
    type: 'string',
    categories: willJson
}
};

    //render chart perestate temporary/ 0 value
      var renderChartTph = new ApexCharts(document.querySelector("#bttinggal"), options);
      renderChartTph.render();

      var renderChartKarung = new ApexCharts(document.querySelector("#karung"), options);
      renderChartKarung.render();

      var renderChartBuahTglTph = new ApexCharts(document.querySelector("#btt_tgl"), options);
      renderChartBuahTglTph.render();

      var renderChartBuahRestanNone = new ApexCharts(document.querySelector("#rst_none"), options);
      renderChartBuahRestanNone.render();

   //render chart perwilayah temporary /0 value
      var will_btt = new ApexCharts(document.querySelector("#btt_id"), will);
      will_btt.render();

      var renderChartKarungWil = new ApexCharts(document.querySelector("#karung_id"), will);
      renderChartKarungWil.render();

      var renderChartBuahTglTphWil = new ApexCharts(document.querySelector("#bttTglTph_id"), will);
      renderChartBuahTglTphWil.render();

      var renderChartBuahRestanNoneWil = new ApexCharts(document.querySelector("#rst_none_id"), will);
      renderChartBuahRestanNoneWil.render();
      
   //////
   getDataTph()

   $("#dateWeek").change(function(event){
    var firstWeek = ''
    var lastWeek = ''
    var _token = $('input[name="_token"]').val();
    
    var weekData = document.getElementById('dateWeek').value

    const year = weekData.substring(0, 4);
    const week = weekData.substring(6, 8);

    const date = new Date(year, 0, 1);
    const date2 = new Date(year, 0, 1);

    var getDateFirst =  date.setDate(date.getDate() + (week - 1) * 7 );
    var getDateLast =  date.setDate(date2.getDate() + (week - 1) * 7 );
  // first week
    var getDateFirst = new Date(getDateFirst)
    var convertFirstWeek = getDateFirst.setDate(getDateFirst.getDate() + 2)
    //mengubah data dari Mon Jan 16 2023 00:00:00 GMT+0700 (Western Indonesia Time) convert javascript to YYYY/MM/DD
    //ke format Tahun/bulan/hari
    var firstWeekCon = new Date(convertFirstWeek);
    let firstWeekData = JSON.stringify(firstWeekCon)
    firstWeek = firstWeekData.slice(1,11) 

    

    // firstWeek = new Date(test2)

  //last week
    var getDateLast = new Date(getDateLast)
    var convertLastWeek = getDateLast.setDate(getDateLast.getDate() + 8)
    //mengubah data dari Mon Jan 16 2023 00:00:00 GMT+0700 (Western Indonesia Time) convert javascript to YYYY/MM/DD
    //ke format Tahun/bulan/hari
    var lastWeekCon = new Date(convertLastWeek);
    let lastWeekData = JSON.stringify(lastWeekCon)
    lastWeek = lastWeekData.slice(1,11) 
});

 



    document.getElementById('btnShow').onclick = function(){
      $('#tbody1').empty()
      $('#tbody2').empty()
      $('#tbody3').empty()

      getDataTph()
      var mybutton = document.getElementById("btnExport");
      mybutton.disabled = true;
      setTimeout(function() {
          mybutton.disabled = false;
      }, 1500);
    }

      function getDataTph () {
      var firstWeek = ''
      var lastWeek = ''
      var _token = $('input[name="_token"]').val();
      
      var weekData = document.getElementById('dateWeek').value

      const year = weekData.substring(0, 4);
      const week = weekData.substring(6, 8);

      const date = new Date(year, 0, 1);
      const date2 = new Date(year, 0, 1);

      var getDateFirst =  date.setDate(date.getDate() + (week - 1) * 7 );
      var getDateLast =  date.setDate(date2.getDate() + (week - 1) * 7 );
    // first week
      var getDateFirst = new Date(getDateFirst)
      var convertFirstWeek = getDateFirst.setDate(getDateFirst.getDate() + 2)
      //mengubah data dari Mon Jan 16 2023 00:00:00 GMT+0700 (Western Indonesia Time) convert javascript to YYYY/MM/DD
      //ke format Tahun/bulan/hari
      var firstWeekCon = new Date(convertFirstWeek);
      let firstWeekData = JSON.stringify(firstWeekCon)
      firstWeek = firstWeekData.slice(1,11) 
      

      // firstWeek = new Date(test2)

    //last week
      var getDateLast = new Date(getDateLast)
      var convertLastWeek = getDateLast.setDate(getDateLast.getDate() + 8)
      //mengubah data dari Mon Jan 16 2023 00:00:00 GMT+0700 (Western Indonesia Time) convert javascript to YYYY/MM/DD
      //ke format Tahun/bulan/hari
      var lastWeekCon = new Date(convertLastWeek);
      let lastWeekData = JSON.stringify(lastWeekCon)
      lastWeek = lastWeekData.slice(1,11) 

      document.getElementById('startWeek').value = firstWeek;
      document.getElementById('lastWeek').value = lastWeek;

      // console.log(firstWeek)
        $.ajax({
            
            url:"{{ route('getBtTph') }}",
            method:"POST",
            data:{ start:firstWeek,finish:lastWeek, _token:_token},
            success:function(result)
            {
              
            // Check if the result is an empty object
            if($.isEmptyObject(result)) {
              result = null;

              renderChartTph.updateSeries([{
                      name: 'Brondolan/Blok Tinggal di TPH',
                      data: [0, 0 , 0, 0, 0, 0, 0 , 0 ,0 , 0 , 0 , 0]
                  }])

                  renderChartKarung.updateSeries([{
                name: 'Karung/Blok  Berisi Brondolan',
                data: [0, 0 , 0, 0, 0, 0, 0 , 0 ,0 , 0 , 0 , 0]
              }])

                  
              renderChartBuahTglTph.updateSeries([{
                name: 'Buah/Blok  Tinggal TPH',
                data: [0, 0 , 0, 0, 0, 0, 0 , 0 ,0 , 0 , 0 , 0]
              }])
                
              renderChartBuahRestanNone.updateSeries([{
                name: 'Restan/Blok  Tidak dilaporkan',
                data: [0, 0 , 0, 0, 0, 0, 0 , 0 ,0 , 0 , 0 , 0]
              }])

                //perwilawyah
              will_btt.updateSeries([{
                name: 'Brondolan Tinggal di TPH',
                data: [0, 0 , 0]
              }]).then(() => {
                window.setTimeout(function() {
                    will_btt.dataURI().then((uri) => {
                      document.getElementById('chartInputData').value = '<div><img src="'+Object.values(uri)+'"></div>';
                    })
                  }, 1000) 
                })

              renderChartKarungWil.updateSeries([{
                name: 'Karung Tinggal di TPH',
                data: [0, 0 , 0]
              }])

              
              renderChartBuahTglTphWil.updateSeries([{
                name: 'Buah Tinggal Di TPH',
                data: [0, 0 , 0]
              }])

                    
              renderChartBuahRestanNoneWil.updateSeries([{
                name: 'Buah Restan Tidak di Laporkan',
                data: [0, 0 , 0]
              }])
            } else {
              //parsing result ke json untuk dalam estate
              var parseResult = JSON.parse(result)

              // console.log(parseResult)
              // ubah json ke array agar bisa di for atau foreach
              var listBtTph = Object.entries(parseResult['val_bt_tph']) //parsing data brondolan ke dalam var list
              // console.log(listBtTph)
              var listKRTph = Object.entries(parseResult['val_kr_tph'])//parsing data karung isi brondolan ke dalam var list
              var lisBHtph = Object.entries(parseResult['val_bh_tph'])//parsing data buah tinggal ke dalam var list
              // console.log(listKRTph)
              var listRStph = Object.entries(parseResult['val_rs_tph']) //parse data dari restand tidak di laporkan
              var listEstate = Object.entries(parseResult['list_estate'])////pasring data estate ke dalam var list
          

                  //parsing result ke json untuk dalam wilayah   
              var listBtTphWil = Object.entries(parseResult['val_bt_tph_wil']) //parsing data brondolan ke dalam var list
              // console.log(listBtTph)
              var listKRTphWil = Object.entries(parseResult['val_kr_tph_wil'])//parsing data karung isi brondolan ke dalam var list
              var lisBHtphWil = Object.entries(parseResult['val_bh_tph_wil'])//parsing data buah tinggal ke dalam var list
              // console.log(listKRTph)
              var listRStphWil = Object.entries(parseResult['val_rs_tph_wil']) //parse data dari restand tidak di laporkan     
              var listWill = Object.entries(parseResult['list_wilayah']) //parse data dari restand tidak di laporkan
              //list untuk table di parse ke json
        
              var list_all_wil = Object.entries(parseResult['list_all_wil']) 
              var list_all_est = Object.entries(parseResult['list_all_est']) 
              var list_skor_gm = Object.entries(parseResult['list_skor_gm']) 
              var skor_rh = parseResult['skor_rh']

            

              //mnghitung dan mengurai string dengan substrack untuk chart
                //brondolan tgl
              var valBtTph = '['
              listBtTph.forEach(element => {
                valBtTph += '"' +element[1] + '",'
              });
              valBtTph = valBtTph.substring(0, valBtTph.length - 1);
              valBtTph += ']'
              var valBtTphJson = JSON.parse(valBtTph)
                //karung tgl
              var valKRtgl = '['
              listKRTph.forEach(element => {
                valKRtgl += '"' +element[1] + '",'
              });
              valKRtgl = valKRtgl.substring(0, valKRtgl.length - 1);
              valKRtgl += ']'
              var valKRTtphJson = JSON.parse(valKRtgl)
                //buah tinggal
              var valBHtgl = '['
              lisBHtph.forEach(element => {
                valBHtgl += '"' +element[1] + '",'
              });
              valBHtgl = valBHtgl.substring(0, valBHtgl.length - 1);
              valBHtgl += ']'
              var valBHtglJson = JSON.parse(valBHtgl)
              // console.log(valBHtglJson)
                //buah restan tidak di laporkan
                
              var valRSnone = '['
              listRStph.forEach(element => {
                valRSnone += '"' +element[1] + '",'
              });
              valRSnone = valRSnone.substring(0, valRSnone.length - 1);
              valRSnone += ']'
              var valRSnoneJson = JSON.parse(valRSnone)


              ///// mengubah data estate agar bisa mengurangi nilai
              var categoryEst = '['
              listEstate.forEach(element => {
                categoryEst += '"' +element [1]['est'] + '",'
              });
              categoryEst = categoryEst.substring(0, categoryEst.length - 1);
              categoryEst += ']'
              // console.log(categoryEst)
              var categoryEstJson = JSON.parse(categoryEst)

              /// mengubah data wilayah agar bisa mengurangi nilai
                //brondolan tgl
                var valBtTphWil = '['
              listBtTphWil.forEach(element => {
                valBtTphWil += '"' +element[1] + '",'
              });
              valBtTphWil = valBtTphWil.substring(0, valBtTphWil.length - 1);
              valBtTphWil += ']'
              var valBtTphWilJson = JSON.parse(valBtTphWil)
              var arrayvalBtTphWilJson = valBtTphWilJson;
                    for (let i = 0; i < arrayvalBtTphWilJson.length; i++) {
                
                      arrayvalBtTphWilJson.splice(3);
                
                  }
              // console.log(array)
              //karung tinggal
              var valKRtglWil = '['
              listKRTphWil.forEach(element => {
                valKRtglWil += '"' +element[1] + '",'
              });
              valKRtglWil = valKRtglWil.substring(0, valKRtglWil.length - 1);
              valKRtglWil += ']'
              var valKRtglWilJson = JSON.parse(valKRtglWil)
              var arrayvalKRtglWilJson = valKRtglWilJson;
                    for (let i = 0; i < arrayvalKRtglWilJson.length; i++) {
                
                      arrayvalKRtglWilJson.splice(3);
                
                  }
              // console.log(valKRtglWilJson)
              //buah tinggal
              var valBHtglWil = '['
              lisBHtphWil.forEach(element => {
                valBHtglWil += '"' +element[1] + '",'
              });
              valBHtglWil = valBHtglWil.substring(0, valBHtglWil.length - 1);
              valBHtglWil += ']'
              var valBHtglWilJson = JSON.parse(valBHtglWil)
              var arrayvalBHtglWilJson = valBHtglWilJson;
                    for (let i = 0; i < arrayvalBHtglWilJson.length; i++) {
                  {
                    arrayvalBHtglWilJson.splice(3);
                    }
                  }

              //buah restant
              var valRSnoneWil = '['
              listRStphWil.forEach(element => {
                valRSnoneWil += '"' +element[1] + '",'
              });
              valRSnoneWil = valRSnoneWil.substring(0, valRSnoneWil.length - 1);
              valRSnoneWil += ']'
              var valRSnoneWilJson = JSON.parse(valRSnoneWil)
              var arrayvalRSnoneWilJson = valRSnoneWilJson;
                    for (let i = 0; i < arrayvalRSnoneWilJson.length; i++) {
                      arrayvalRSnoneWilJson.splice(3);
                        }
                  
            
              var categoryWill = '['
              listWill.forEach(element => {
                categoryWill += '"' +element [1]['nama'] + '",'
              });
              categoryWill = categoryWill.substring(0, categoryWill.length - 1);
              categoryWill += ']'
              var categoryWillJson = JSON.parse(categoryWill)
              // console.log(categoryWillJson)
              
              //bagian untuk update chart ketika chart tidak ada isinya menjadi nilai 0
            //persetate
              renderChartTph.updateSeries([{
                    name: 'Brondolan/Blok Tinggal di TPH',
                    data: valBtTphJson
                }])

                renderChartKarung.updateSeries([{
              name: 'Karung/Blok  Berisi Brondolan',
              data: valKRTtphJson
            }])

                
            renderChartBuahTglTph.updateSeries([{
              name: 'Buah/Blok  Tinggal TPH',
              data: valBHtglJson
            }])
              
            renderChartBuahRestanNone.updateSeries([{
              name: 'Restan/Blok  Tidak dilaporkan',
              data: valRSnoneJson
            }])

              //perwilawyah
            will_btt.updateSeries([{
              name: 'Brondolan Tinggal di TPH',
              data: arrayvalBtTphWilJson
            }]).then(() => {
              window.setTimeout(function() {
                  will_btt.dataURI().then((uri) => {
                    document.getElementById('chartInputData').value = '<div><img src="'+Object.values(uri)+'"></div>';
                  })
                }, 1000) 
              })

            renderChartKarungWil.updateSeries([{
              name: 'Karung Tinggal di TPH',
              data: arrayvalKRtglWilJson
            }])

            
            renderChartBuahTglTphWil.updateSeries([{
              name: 'Buah Tinggal Di TPH',
              data: arrayvalBHtglWilJson
            }])

                  
            renderChartBuahRestanNoneWil.updateSeries([{
              name: 'Buah Restan Tidak di Laporkan',
              data: arrayvalRSnoneWilJson
            }])


    //          //untuk table
              //table wil 1
            var arrTbody1 = list_all_wil[0][1]
            var table1 = document.getElementById('table1');
              var tbody1 = document.getElementById('tbody1');

              // console.log(arrTbody1)
              arrTbody1.forEach(element => {
                // for (let i = 0; i < 5; i++) {
                  
                  tr = document.createElement('tr')
                  let item1 = element['est']
                  let item2 = element['afd']
                  let item3 = element['nama']
                  let item4 = element['skor']
                  let item5 = element['rank']
                  

                  let itemElement1 = document.createElement('td')
                  let itemElement2 = document.createElement('td')
                  let itemElement3 = document.createElement('td')
                  let itemElement4 = document.createElement('td')
                  let itemElement5 = document.createElement('td')

                

                  itemElement1.classList.add("text-center")
        itemElement2.classList.add("text-center")
        itemElement3.classList.add("text-center")
        itemElement4.classList.add("text-center")
        itemElement5.classList.add("text-center")



        if (item4 >= 95) {
                    itemElement4.style.backgroundColor = "#609cd4";
        } else if (item4 >= 85 && item4 < 95) {
          itemElement4.style.backgroundColor = "#08b454";
        } else if (item4 >= 75 && item4 < 85) {
          itemElement4.style.backgroundColor = "#fffc04";
        } else if (item4 >= 65 && item4 < 75) {
            itemElement4.style.backgroundColor = "#ffc404";
        } else {
            itemElement4.style.backgroundColor = "red";
        }

        if(itemElement4.style.backgroundColor === "#609cd4"){
        itemElement4.style.color = "white";
    }
    else if(itemElement4.style.backgroundColor === "#08b454"){
        itemElement4.style.color = "white";
    }
    else if(itemElement4.style.backgroundColor === "#fffc04"){
        itemElement4.style.color = "black";
    }
    else if(itemElement4.style.backgroundColor === "#ffc404"){
        itemElement4.style.color = "black";
    }
    else if(itemElement4.style.backgroundColor === "red"){
        itemElement4.style.color = "white";
    }


                      itemElement4.innerText = item4;
                      itemElement1.innerText  = item1
                      itemElement2.innerText  = item2
                      itemElement3.innerText  = item3
                      
                      if (item4 != 0) {    
                        itemElement4.innerHTML = '<a href="detailSidakTph/' + element['est']+ '/'+ element['afd'] +'/'+ firstWeek+ '/'+lastWeek+'">' + element['skor'] + ' </a>'
                      }else{
                        itemElement4.innerText  = item4
                      }
                      itemElement5.innerText  = item5

                  tr.appendChild(itemElement1)
                  tr.appendChild(itemElement2)
                  tr.appendChild(itemElement3)
                  tr.appendChild(itemElement4)
                  tr.appendChild(itemElement5)

                  tbody1.appendChild(tr)
                // }
              });
    //  testing
    var arrTbody1 = list_all_est[0][1]
            var table1 = document.getElementById('table1');
              var tbody1 = document.getElementById('tbody1');

              // console.log(arrTbody1)
              arrTbody1.forEach(element => {
                // for (let i = 0; i < 5; i++) {
                  
                  tr = document.createElement('tr')
                  let item1 = element['est']
                  let item2 = element['EM']
                  let item3 = element['nama']
                  let item4 = element['skor']
                  let item5 = element['rank']
                  

                  let itemElement1 = document.createElement('td')
                  let itemElement2 = document.createElement('td')
                  let itemElement3 = document.createElement('td')
                  let itemElement4 = document.createElement('td')
                  let itemElement5 = document.createElement('td')

                

                  itemElement1.classList.add("text-center")
        itemElement2.classList.add("text-center")
        itemElement3.classList.add("text-center")
        itemElement4.classList.add("text-center")
        itemElement5.classList.add("text-center")


        itemElement1.style.backgroundColor = "#e8ecdc";
        itemElement2.style.backgroundColor = "#e8ecdc";
        itemElement3.style.backgroundColor = "#e8ecdc";
        if (item4 >= 95) {
                    itemElement4.style.backgroundColor = "#609cd4";
        } else if (item4 >= 85 && item4 < 95) {
          itemElement4.style.backgroundColor = "#08b454";
        } else if (item4 >= 75 && item4 < 85) {
          itemElement4.style.backgroundColor = "#fffc04";
        } else if (item4 >= 65 && item4 < 75) {
            itemElement4.style.backgroundColor = "#ffc404";
        } else {
            itemElement4.style.backgroundColor = "red";
        }

        if(itemElement4.style.backgroundColor === "#609cd4"){
        itemElement4.style.color = "white";
    }
    else if(itemElement4.style.backgroundColor === "#08b454"){
        itemElement4.style.color = "white";
    }
    else if(itemElement4.style.backgroundColor === "#fffc04"){
        itemElement4.style.color = "black";
    }
    else if(itemElement4.style.backgroundColor === "#ffc404"){
        itemElement4.style.color = "black";
    }
    else if(itemElement4.style.backgroundColor === "red"){
        itemElement4.style.color = "white";
    }


                      itemElement4.innerText = item4;
                      itemElement1.innerText  = item1
                      itemElement2.innerText  = item2
                      itemElement3.innerText  = item3
                      itemElement4.innerText  = item4
                      itemElement5.innerText  = item5

                  tr.appendChild(itemElement1)
                  tr.appendChild(itemElement2)
                  tr.appendChild(itemElement3)
                  tr.appendChild(itemElement4)
                  tr.appendChild(itemElement5)

                  tbody1.appendChild(tr)
                // }
              });

    // endtesting
              
              ///table wil 2
              var arrTbody2 = list_all_wil[1][1]
              // console.log(list_all_wil)
            //  var table1 = document.getElementById('table1');
              var tbody2 = document.getElementById('tbody2');

            
              arrTbody2.forEach(element => {
                // for (let i = 0; i < 5; i++) {
                  
                  tr = document.createElement('tr')
                  let item1 = element['est']
                  let item2 = element['afd']
                  let item3 = element['nama']
                  let item4 = element['skor']
                  let item5 = element['rank']

                  let itemElement1 = document.createElement('td')
                  let itemElement2 = document.createElement('td')
                  let itemElement3 = document.createElement('td')
                  let itemElement4 = document.createElement('td')
                  let itemElement5 = document.createElement('td')
                  itemElement1.classList.add("text-center")
                itemElement2.classList.add("text-center")
                itemElement3.classList.add("text-center")
        itemElement4.classList.add("text-center")
        itemElement5.classList.add("text-center")


          if (item4 >= 95) {
                    itemElement4.style.backgroundColor = "#609cd4";
        } else if (item4 >= 85 && item4 < 95) {
          itemElement4.style.backgroundColor = "#08b454";
        } else if (item4 >= 75 && item4 < 85) {
          itemElement4.style.backgroundColor = "#fffc04";
        } else if (item4 >= 65 && item4 < 75) {
            itemElement4.style.backgroundColor = "#ffc404";
        } else {
            itemElement4.style.backgroundColor = "red";
        }

        if(itemElement4.style.backgroundColor === "#609cd4"){
        itemElement4.style.color = "white";
    }
    else if(itemElement4.style.backgroundColor === "#08b454"){
        itemElement4.style.color = "white";
    }
    else if(itemElement4.style.backgroundColor === "#fffc04"){
        itemElement4.style.color = "black";
    }
    else if(itemElement4.style.backgroundColor === "#ffc404"){
        itemElement4.style.color = "black";
    }
    else if(itemElement4.style.backgroundColor === "red"){
        itemElement4.style.color = "white";
    }


                  itemElement1.innerText  = item1
                  itemElement2.innerText  = item2
                  itemElement3.innerText  = item3
                  // itemElement4.innerText  = item4
                  // itemElement4.innerHTML = '<a href="detaiSidakTph/' + element['est']+ '/'+ element['afd'] +'/'+ firstWeek+ '/'+lastWeek+'">' + element['skor'] + ' </a>'
                  if (item4 != 0) {    
                        itemElement4.innerHTML = '<a href="detailSidakTph/' + element['est']+ '/'+ element['afd'] +'/'+ firstWeek+ '/'+lastWeek+'">' + element['skor'] + ' </a>'
                      }else{
                        itemElement4.innerText  = item4
                      }
                  itemElement5.innerText  = item5

                  tr.appendChild(itemElement1)
                  tr.appendChild(itemElement2)
                  tr.appendChild(itemElement3)
                  tr.appendChild(itemElement4)
                  tr.appendChild(itemElement5)

                  tbody2.appendChild(tr)
                // }
              });
              //untuk estate wil 2
              var arrTbody1 = list_all_est[1][1]
              var tbody1 = document.getElementById('tbody2');

              // console.log(arrTbody1)
              arrTbody1.forEach(element => {
                // for (let i = 0; i < 5; i++) {
                  
                  tr = document.createElement('tr')
                  let item1 = element['est']
                  let item2 = element['EM']
                  let item3 = element['nama']
                  let item4 = element['skor']
                  let item5 = element['rank']
                  

                  let itemElement1 = document.createElement('td')
                  let itemElement2 = document.createElement('td')
                  let itemElement3 = document.createElement('td')
                  let itemElement4 = document.createElement('td')
                  let itemElement5 = document.createElement('td')

                

                  itemElement1.classList.add("text-center")
        itemElement2.classList.add("text-center")
        itemElement3.classList.add("text-center")
        itemElement4.classList.add("text-center")
        itemElement5.classList.add("text-center")


        itemElement1.style.backgroundColor = "#e8ecdc";
        itemElement2.style.backgroundColor = "#e8ecdc";
        itemElement3.style.backgroundColor = "#e8ecdc";
        if (item4 >= 95) {
                    itemElement4.style.backgroundColor = "#609cd4";
        } else if (item4 >= 85 && item4 < 95) {
          itemElement4.style.backgroundColor = "#08b454";
        } else if (item4 >= 75 && item4 < 85) {
          itemElement4.style.backgroundColor = "#fffc04";
        } else if (item4 >= 65 && item4 < 75) {
            itemElement4.style.backgroundColor = "#ffc404";
        } else {
            itemElement4.style.backgroundColor = "red";
        }

        if(itemElement4.style.backgroundColor === "#609cd4"){
        itemElement4.style.color = "white";
    }
    else if(itemElement4.style.backgroundColor === "#08b454"){
        itemElement4.style.color = "white";
    }
    else if(itemElement4.style.backgroundColor === "#fffc04"){
        itemElement4.style.color = "black";
    }
    else if(itemElement4.style.backgroundColor === "#ffc404"){
        itemElement4.style.color = "black";
    }
    else if(itemElement4.style.backgroundColor === "red"){
        itemElement4.style.color = "white";
    }


                      itemElement4.innerText = item4;
                      itemElement1.innerText  = item1
                      itemElement2.innerText  = item2
                      itemElement3.innerText  = item3
                      itemElement4.innerText  = item4
                      itemElement5.innerText  = item5

                  tr.appendChild(itemElement1)
                  tr.appendChild(itemElement2)
                  tr.appendChild(itemElement3)
                  tr.appendChild(itemElement4)
                  tr.appendChild(itemElement5)

                  tbody1.appendChild(tr)
                // }
              });

           

      ///table wil 3
      var arrTbody3 = list_all_wil[2][1]
              // console.log(list_all_wil)
            //  var table1 = document.getElementById('table1');
              var tbody3 = document.getElementById('tbody3');

            
              arrTbody3.forEach(element => {
                // for (let i = 0; i < 5; i++) {
                  
                  tr = document.createElement('tr')
                  let item1 = element['est']
                  let item2 = element['afd']
                  let item3 = element['nama']
                  let item4 = element['skor']
                  let item5 = element['rank']

                  let itemElement1 = document.createElement('td')
                  let itemElement2 = document.createElement('td')
                  let itemElement3 = document.createElement('td')
                  let itemElement4 = document.createElement('td')
                  let itemElement5 = document.createElement('td')

                  itemElement1.classList.add("text-center")
                  itemElement2.classList.add("text-center")
                  itemElement3.classList.add("text-center")
                  itemElement4.classList.add("text-center")
                  itemElement5.classList.add("text-center")

              
          if (item4 >= 95) {
                    itemElement4.style.backgroundColor = "#609cd4";
        } else if (item4 >= 85 && item4 < 95) {
          itemElement4.style.backgroundColor = "#08b454";
        } else if (item4 >= 75 && item4 < 85) {
          itemElement4.style.backgroundColor = "#fffc04";
        } else if (item4 >= 65 && item4 < 75) {
            itemElement4.style.backgroundColor = "#ffc404";
        } else {
            itemElement4.style.backgroundColor = "red";
        }

        if(itemElement4.style.backgroundColor === "#609cd4"){
        itemElement4.style.color = "white";
    }
    else if(itemElement4.style.backgroundColor === "#08b454"){
        itemElement4.style.color = "white";
    }
    else if(itemElement4.style.backgroundColor === "#fffc04"){
        itemElement4.style.color = "black";
    }
    else if(itemElement4.style.backgroundColor === "#ffc404"){
        itemElement4.style.color = "black";
    }
    else if(itemElement4.style.backgroundColor === "red"){
        itemElement4.style.color = "white";
    }


                  itemElement1.innerText  = item1
                  itemElement2.innerText  = item2
                  itemElement3.innerText  = item3
                  // itemElement4.innerText  = item4
                  // itemElement4.innerHTML = '<a href="detailSidakTph/' + element['est']+ '/'+ element['afd'] +'/'+ firstWeek+ '/'+lastWeek+'">' + element['skor'] + ' </a>'
                  if (item4 != 0) {    
                        itemElement4.innerHTML = '<a href="detailSidakTph/' + element['est']+ '/'+ element['afd'] +'/'+ firstWeek+ '/'+lastWeek+'">' + element['skor'] + ' </a>'
                      }else{
                        itemElement4.innerText  = item4
                      }
                  itemElement5.innerText  = item5

                  tr.appendChild(itemElement1)
                  tr.appendChild(itemElement2)
                  tr.appendChild(itemElement3)
                  tr.appendChild(itemElement4)
                  tr.appendChild(itemElement5)

                  tbody3.appendChild(tr)
                // }
              });

              // untuk estate will 3
              var arrTbody1 = list_all_est[2][1]
              var tbody1 = document.getElementById('tbody3');

              // console.log(arrTbody1)
              arrTbody1.forEach(element => {
                // for (let i = 0; i < 5; i++) {
                  
                  tr = document.createElement('tr')
                  let item1 = element['est']
                  let item2 = element['EM']
                  let item3 = element['nama']
                  let item4 = element['skor']
                  let item5 = element['rank']
                  

                  let itemElement1 = document.createElement('td')
                  let itemElement2 = document.createElement('td')
                  let itemElement3 = document.createElement('td')
                  let itemElement4 = document.createElement('td')
                  let itemElement5 = document.createElement('td')

                

        itemElement1.classList.add("text-center")
        itemElement2.classList.add("text-center")
        itemElement3.classList.add("text-center")
        itemElement4.classList.add("text-center")
        itemElement5.classList.add("text-center")


        itemElement1.style.backgroundColor = "#e8ecdc";
        itemElement2.style.backgroundColor = "#e8ecdc";
        itemElement3.style.backgroundColor = "#e8ecdc";
        if (item4 >= 95) {
                    itemElement4.style.backgroundColor = "#609cd4";
        } else if (item4 >= 85 && item4 < 95) {
          itemElement4.style.backgroundColor = "#08b454";
        } else if (item4 >= 75 && item4 < 85) {
          itemElement4.style.backgroundColor = "#fffc04";
        } else if (item4 >= 65 && item4 < 75) {
            itemElement4.style.backgroundColor = "#ffc404";
        } else {
            itemElement4.style.backgroundColor = "red";
        }

        if(itemElement4.style.backgroundColor === "#609cd4"){
        itemElement4.style.color = "white";
    }
    else if(itemElement4.style.backgroundColor === "#08b454"){
        itemElement4.style.color = "white";
    }
    else if(itemElement4.style.backgroundColor === "#fffc04"){
        itemElement4.style.color = "black";
    }
    else if(itemElement4.style.backgroundColor === "#ffc404"){
        itemElement4.style.color = "black";
    }
    else if(itemElement4.style.backgroundColor === "red"){
        itemElement4.style.color = "white";
    }


                      itemElement4.innerText = item4;
                      itemElement1.innerText  = item1
                      itemElement2.innerText  = item2
                      itemElement3.innerText  = item3
                      itemElement4.innerText  = item4
                      itemElement5.innerText  = item5

                  tr.appendChild(itemElement1)
                  tr.appendChild(itemElement2)
                  tr.appendChild(itemElement3)
                  tr.appendChild(itemElement4)
                  tr.appendChild(itemElement5)

                  tbody1.appendChild(tr)
                // }
              });

              var inc = 0;
              for (let i = 1; i <= 3; i++) {  
                var tbody = document.getElementById('tbody' + i);
              
               var wil = ''
               if(i == 1){
                 wil = 'I'
               }else if(i == 2){
                wil = 'II'
               }else{
                 wil = 'III'
               }

                tr = document.createElement('tr')
                  let item1 = 'WIL-' + wil
                  let item2 = 'GM'
                  let item3 = list_skor_gm[inc][1]['nama']
                  let item4 = list_skor_gm[inc][1]['skor']
                  let item5 = ''
                  

                  let itemElement1 = document.createElement('td')
                  let itemElement2 = document.createElement('td')
                  let itemElement3 = document.createElement('td')
                  let itemElement4 = document.createElement('td')
                  let itemElement5 = document.createElement('td')

                

                  itemElement1.classList.add("text-center")
                  itemElement2.classList.add("text-center")
                  itemElement3.classList.add("text-center")
                  itemElement4.classList.add("text-center")
                  itemElement5.classList.add("text-center")


                  itemElement1.style.backgroundColor = "#fff4cc";
                  itemElement2.style.backgroundColor = "#fff4cc";
                  itemElement3.style.backgroundColor = "#fff4cc";
                  if (item4 >= 95) {
                              itemElement4.style.backgroundColor = "#609cd4";
                  } else if (item4 >= 85 && item4 < 95) {
                    itemElement4.style.backgroundColor = "#08b454";
                  } else if (item4 >= 75 && item4 < 85) {
                    itemElement4.style.backgroundColor = "#fffc04";
                  } else if (item4 >= 65 && item4 < 75) {
                      itemElement4.style.backgroundColor = "#ffc404";
                  } else {
                      itemElement4.style.backgroundColor = "red";
                  }

                  if(itemElement4.style.backgroundColor === "#609cd4"){
                  itemElement4.style.color = "white";
              }
              else if(itemElement4.style.backgroundColor === "#08b454"){
                  itemElement4.style.color = "white";
              }
              else if(itemElement4.style.backgroundColor === "#fffc04"){
                  itemElement4.style.color = "black";
              }
              else if(itemElement4.style.backgroundColor === "#ffc404"){
                  itemElement4.style.color = "black";
              }
              else if(itemElement4.style.backgroundColor === "red"){
                  itemElement4.style.color = "white";
              }


                      itemElement4.innerText = item4;
                      itemElement1.innerText  = item1
                      itemElement2.innerText  = item2
                      itemElement3.innerText  = item3
                      itemElement4.innerText  = item4
                      itemElement5.innerText  = item5

                  tr.appendChild(itemElement1)
                  tr.appendChild(itemElement2)
                  tr.appendChild(itemElement3)
                  tr.appendChild(itemElement4)
                  tr.appendChild(itemElement5)

                  tbody.appendChild(tr)
                  inc++
              }

              // console.log(skor_rh)
              tbodySkorRH = document.getElementById('tbodySkorRH')
                tr = document.createElement('tr')
                  let item1 = 'REG I'
                  let item2 = 'RH - 1'
                  let item3 = 'AKHMAD FAISYAL'
                  let item4 = skor_rh
                
                  let itemElement1 = document.createElement('td')
                  let itemElement2 = document.createElement('td')
                  let itemElement3 = document.createElement('td')
                  let itemElement4 = document.createElement('td')
            
                  itemElement1.classList.add("text-center")
                  itemElement2.classList.add("text-center")
                  itemElement3.classList.add("text-center")
                  itemElement4.classList.add("text-center")
               
                  itemElement1.style.backgroundColor = "#e8ecdc";
                  itemElement2.style.backgroundColor = "#e8ecdc";
                  itemElement3.style.backgroundColor = "#e8ecdc";
                  if (item4 >= 95) {
                              itemElement4.style.backgroundColor = "#609cd4";
                  } else if (item4 >= 85 && item4 < 95) {
                    itemElement4.style.backgroundColor = "#08b454";
                  } else if (item4 >= 75 && item4 < 85) {
                    itemElement4.style.backgroundColor = "#fffc04";
                  } else if (item4 >= 65 && item4 < 75) {
                      itemElement4.style.backgroundColor = "#ffc404";
                  } else {
                      itemElement4.style.backgroundColor = "red";
                  }

                  if(itemElement4.style.backgroundColor === "#609cd4"){
                  itemElement4.style.color = "white";
              }
              else if(itemElement4.style.backgroundColor === "#08b454"){
                  itemElement4.style.color = "white";
              }
              else if(itemElement4.style.backgroundColor === "#fffc04"){
                  itemElement4.style.color = "black";
              }
              else if(itemElement4.style.backgroundColor === "#ffc404"){
                  itemElement4.style.color = "black";
              }
              else if(itemElement4.style.backgroundColor === "red"){
                  itemElement4.style.color = "white";
              }


                      itemElement4.innerText = item4;
                      itemElement1.innerText  = item1
                      itemElement2.innerText  = item2
                      itemElement3.innerText  = item3
                 
               

                  tr.appendChild(itemElement1)
                  tr.appendChild(itemElement2)
                  tr.appendChild(itemElement3)
                  tr.appendChild(itemElement4)


                  tbodySkorRH.appendChild(tr)


      
    //end table

              }
            }
            }
            )
    }
});

function chartBtTph(categoryEst,
 categoryWill,
  valBtTph,
   valKRtgl, 
   valBHtgl, 
   valRSnone,
   valBtTphWil,
   valKRtglWil,
   valBHtglWil,
   valRSnoneWil
    ){

  //perestate
  //brondolan tinggal
  renderChartTph.updateSeries([{
                name: 'Brondolan Tinggal di TPH',
                data: valBtTph
            }])
          
  //karung berisi brondolam
       renderChartKarung.updateSeries([{
          name: 'Karung Berisi Brondolan',
           data: valKRtgl
         }])
       //buah tinggal tph
         renderChartBuahTglTph.updateSeries([{
          name: 'Buah Tinggal TPH',
           data: valBHtgl
         }])
//restan tidak di laporkan
         renderChartBuahRestanNone.updateSeries([{
          name: 'Restant tidak di laporkan',
           data: valRSnone
         }])

         //perwilayah
         //brondolan tinggal
         will_btt.updateSeries([{
          name: 'Brondolan Tinggal di TPH',
           data: valBtTphWil
         }])
    //karung berisi brondolam
    renderChartKarungWil.updateSeries([{
          name: 'Karung Berisi Brondolan',
           data: valKRtglWil
         }])
       //buah tinggal tph
         renderChartBuahTglTphWil.updateSeries([{
          name: 'Buah Tinggal TPH',
           data: valBHtglWil
         }])
//restan tidak di laporkan
         renderChartBuahRestanNoneWil.updateSeries([{
          name: 'Restant tidak di laporkan',
           data: valRSnoneWil
         }])
 // contoh data array

 
}
//export ke pdf
</script>