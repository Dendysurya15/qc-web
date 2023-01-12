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

    div.scroll {
        margin: 4px, 4px;
        padding: 4px;

        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
    }

    .pagenumbers {

        margin-top: 30px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    .pagenumbers button {
        width: 50px;
        height: 50px;

        appearance: none;
        border-radius: 5px;
        border: 1px solid white;
        outline: none;
        cursor: pointer;

        background-color: white;

        margin: 5px;
        transition: 0.4s;

        color: black;
        font-size: 18px;
        text-shadow: 0px 0px 4px rgba(0, 0, 0, 0.2);
        box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.2);
    }

    .pagenumbers button:hover {
        background-color: #013c5e;
        color: white
    }

    .pagenumbers button.active {
        background-color: #013c5e;
        color: white;
        box-shadow: inset 0px 0px 4px rgba(0, 0, 0, 0.2);
    }

    .pagenumbers button.active:hover {
        background-color: #353e44;
        color: white;
        box-shadow: inset 0px 0px 4px rgba(0, 0, 0, 0.2);
    }

    .table_wrapper {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    td:first-child,
    th:first-child {
        position: sticky;
        left: 0;
        background-color: white;
    }

    td:nth-child(2),
    th:nth-child(2) {
        position: sticky;
        left: 4.5%;
        background-color: white;
    }


    td:nth-child(3),
    th:nth-child(3) {
        position: sticky;
        left: 10%;
        background-color: white;
    }

    td:nth-child(4),
    th:nth-child(4) {
        position: sticky;
        left: 13%;
        background-color: white;
    }

    td:nth-child(5),
    th:nth-child(5) {
        position: sticky;
        left: 15.5%;
        background-color: white;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <br>
        <div class="row">

            <div class="col-2">
                {{csrf_field()}}

                <select name="" class="form-control" id="regionalData">
                    <option value="" disabled selected>Pilih Regional</option>
                    <option value="1">Regional 1</option>
                    <option value="2">Regional 2</option>
                    <option value="3">Regional 3</option>
                </select>
            </div>
            <div class="col-2">

                <select name="" class="form-control" id="yearData">
                    <option value="" disabled selected>Pilih Tahun</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                </select>
            </div>

        </div>
        <br>
        <div class="container-fluid">
            <div class="scroll">

                <div class="card table_wrapper">

                    <table id="tableData" class="table table-stripped" class="display" style="width:100%">
                        <tbody id="list" class="list">
                        </tbody>
                    </table>

                </div>
                <div class="pagenumbers" id="pagination"></div>

            </div>
        </div>
    </section>
</div>
@include('layout/footer')

<script type="text/javascript">
    $(document).ready(function () {
    // var t = $('#tableData').DataTable({
    //     "scrollX": true,
    //     "pageLength": 25
    // });

    // var regional = ''
    // $('#regionalData').change(function(){
    //     regional = $(this).val();
        
    //     // getData(year)
    // });


    $('#yearData').change(function(){
        year = $(this).val();
        regional = document.getElementById('regionalData').value;
        

        console.log(regional)
        // getData(year)
    });


    function getData(year){

        var value = year;
        var _token = $('input[name="_token"]').val();

        $.ajax({
                url:"{{ route('getDataByYear') }}",
                method:"POST",
                data:{ year:value, _token:_token},
                success:function(result)
                {  
                    var result = JSON.parse(result);

                    
                
                    // $('#tableData').dataTable().fnClearTable();
                    //delete thead
                    document.getElementById("tableData").deleteTHead();

                    var arrHeader = Object.entries(result['arrHeader'])
                    var arrMonth = Object.entries(result['arrMonth'])
                     
                    var rowHeader = '['
                    for (let i = 0; i < arrHeader.length; i++) {
                    rowHeader += '"' +result['arrHeader'][i]+ '",'
                    }
                    

                    rowHeader = rowHeader.substring(0, rowHeader.length -1)
                    rowHeader += ']'

                    var parserowHeader  = JSON.parse(rowHeader)

                    var thead = document.createElement('thead');
                    var table = document.getElementById('tableData')
                    table.appendChild(thead);

                        for (var i=0; i<parserowHeader.length; i++) {
                            thead.appendChild(document.createElement("th")).
                                appendChild(document.createTextNode(parserowHeader[i]));
                        }

                    // var yourTable = document.querySelector('table'); // select your table
                    // var row = document.createElement('tr');
                    // for (var i=0; i<parserowHeader.length; i++) {
                    //     var cell = document.createElement("th");
                    //     cell.innerHTML = parserowHeader[i];
                    //     row.appendChild(cell);
                    // }
                    // thead.appendChild(row);
                    // yourTable.insertBefore(thead, yourTable.children[0]);

                    const pagination_element = document.getElementById('pagination')
                    var list_element = document.getElementById('list')

                    let current_page = 1;
                    let rows = 12;

                    
                    // function DisplayList(items, wrapper, rows_per_page, page){
                    // wrapper.innerHTML = "";
                    // page--;

                    // let start = rows_per_page * page;
                    // let end = start + rows_per_page;

                    // var item = 'askdfksd'
                    // var tr = document.createElement('tr');
                    // var item_element = document.createElement('td')
                    // item_element.innerText = item
                    // tr.appendChild(item_element)
                    // list_element.appendChild(tr)

                    var arrId = Object.entries(result['arrId'])

                    // var arrIdLink = new Array()
                    // arrId.forEach(element => {
                    //     var childArr = Object.entries(element[1])
                    //     for (let i = 0; i < childArr.length; i++) {
                            
                    //         // console.log(childArr)
                    //         arrIdLink.push(childArr[i][1])
                    //     }
                    // });


                    // console.log(arrIdLink)

                    var arrResult = Object.entries(result['arrView'])
                    var arrId = Object.entries(result['arrId'])
                    var tableData = document.getElementById('tableData');
                    if ($("#tableData > tbody > tr").length != 0) {
                        for (let i = 0; i < arrResult.length; i++) {
                            tableData.deleteRow(0)
                        }
                    }
                    
                    
                  
                    // var tb = document.querySelectorAll('tbody');
                    // for (var i = 0; i < tb.length; i++) {
                    //     if (tb[i].children.length === 0) {
                    //     tb[i].parentNode.removeChild(tb[i]);
                    //     }
                    // }
                  
                    
                    // arrResult.forEach(element => {
                        for (let i = 0; i < arrResult.length; i++) {
                            
                            
                        var childArrView = Object.entries(arrResult[i][1])
                        var childArrId = Object.entries(arrId[i][1])

                        var tr = document.createElement('tr');
                        
                        tr.setAttribute("id", "testing" + childArrView[0]);

                        // childArrView.forEach(element => {
                            
                     
                        for (let j = 0; j < childArrView.length; j++) {
                            
                            // var item = 'item' + arrResult[i][0]
                            
                            if (childArrView[j][1] == '-' ) {
                                // console.log(childArrId);
                                if (childArrView[j][1] == '-') {
                                    var dt = '0'
                                }

                                var item_element = document.createElement('td')
                                item_element.innerHTML = '<a href="detailInspeksi/' + childArrId[j][1] + '">' + dt + ' </a>'
                                tr.appendChild(item_element);
                            } else if (childArrId[j][1] != '-') {
                                var item_element = document.createElement('td')
                                item_element.innerHTML = '<a href="detailInspeksi/' + childArrId[j][1] + '">' + childArrView[j][1] + ' </a>'
                                tr.appendChild(item_element);
                            } else {
                                var item = childArrView[j][1]
                                var item_element = 'item_element' +  arrResult[i][0]
                            
                            var item_element = document.createElement('td')
                            item_element.innerText = item

                            tr.appendChild(item_element);
                            }

                          
                        }
                    // });
                        list_element.appendChild(tr)
                   

                    // });
                    }

                 

                    // function DisplayList(items, wrapper, rows_per_page, page){
                    //     wrapper.innerHTML = "";
                    //     page--;

                    //     let start = rows_per_page * page;
                    //     let end = start + rows_per_page;
                    //     let paginatedItems = items.slice(start, end);



                    //     console.log(paginatedItems)
                    //     let inc = 1;
                    //     for (let i = 0; i < paginatedItems.length; i++) {
                    //         let item = inc
                    //         let item2 = paginatedItems[i]['tanggal_formatted']
                    //         let item3 = paginatedItems[i]['lokasi_kerja']
                    //         let item4 = paginatedItems[i]['afdeling']
                    //         let item5 = paginatedItems[i]['blok']
                    //         let item6 = paginatedItems[i]['akp']
                    //         let item7 = paginatedItems[i]['taksasi']
                    //         let item8 = paginatedItems[i]['ritase']
                    //         let item9 = paginatedItems[i]['pemanen']
                    //         let item10 = paginatedItems[i]['luas']
                    //         let item11 = paginatedItems[i]['sph']
                    //         let item12 = paginatedItems[i]['bjr']
                    //         let item13 = paginatedItems[i]['jumlah_path']
                    //         let item14 = paginatedItems[i]['jumlah_janjang']
                    //         let item15 = paginatedItems[i]['jumlah_pokok']
                    //         let item16 = paginatedItems[i]['tanggal_formatted']

                    //         var tr = document.createElement('tr');
                    //         let item_element = document.createElement('td')
                    //         let item_element2 = document.createElement('td')
                    //         let item_element3 = document.createElement('td')
                    //         let item_element4 = document.createElement('td')
                    //         let item_element5 = document.createElement('td')
                    //         let item_element6 = document.createElement('td')
                    //         let item_element7 = document.createElement('td')
                    //         let item_element8 = document.createElement('td')
                    //         let item_element9 = document.createElement('td')
                    //         let item_element10 = document.createElement('td')
                    //         let item_element11 = document.createElement('td')
                    //         let item_element12 = document.createElement('td')
                    //         let item_element13 = document.createElement('td')
                    //         let item_element14 = document.createElement('td')
                    //         let item_element15 = document.createElement('td')
                    //         let item_element16 = document.createElement('td')

                    //         // item_element.classList.add('item')
                    //         item_element.innerText = item
                    //         item_element2.innerText = item2
                    //         item_element3.innerText = item3
                    //         item_element4.innerText = item4
                    //         item_element5.innerText = item5
                    //         item_element6.innerText = item6
                    //         item_element7.innerText = item7
                    //         item_element7.innerText = item7
                    //         item_element8.innerText = item8
                    //         item_element9.innerText = item9
                    //         item_element10.innerText = item10
                    //         item_element11.innerText = item11
                    //         item_element12.innerText = item12
                    //         item_element13.innerText = item13
                    //         item_element14.innerText = item14
                    //         item_element15.innerText = item15
                    //         item_element16.innerText = item16

                    //         tr.appendChild(item_element);
                    //         tr.appendChild(item_element2);
                    //         tr.appendChild(item_element3);
                    //         tr.appendChild(item_element4);
                    //         tr.appendChild(item_element5);
                    //         tr.appendChild(item_element6);
                    //         tr.appendChild(item_element7);
                    //         tr.appendChild(item_element8);
                    //         tr.appendChild(item_element9);
                    //         tr.appendChild(item_element10);
                    //         tr.appendChild(item_element11);
                    //         tr.appendChild(item_element12);
                    //         tr.appendChild(item_element13);
                    //         tr.appendChild(item_element14);
                    //         tr.appendChild(item_element15);
                    //         wrapper.appendChild(tr)
                    //         inc++
                    //     }
                    // }

                        


                    // t.row.add(parserowHeader).draw()
                    // $('#tableData').DataTable( {
                    //     data: data,
                    //     columns: [
                    //         { data: 'name' },
                    //         { data: 'position' },
                    //         { data: 'salary' },
                    //         { data: 'office' }
                    //     ]
                    // } );

                    // var arrResult = Object.entries(result)

                    // // console.log(arrResult);
                    // var parseRowVal = ''
                    // arrResult.forEach(element => {
                    //     var childArr = Object.entries(element[1])
                    //     var rowVal = '['
                    //     childArr.forEach(val => {
                    //         rowVal += '"' + val[1]+ '",'
                    //     });
                    //     rowVal = rowVal.substring(0, rowVal.length -1)
                    //     rowVal += ']'
                    //     var parseRowVal = JSON.parse(rowVal)

                    //     t.row.add(parseRowVal).draw()

                    // });
                    // t.row.add([10,2]).draw()

                    // arrResult.forEach(element => {
                    //     console.log(element)
                    //     var childArr = Object.entries(element[1])

                    //     childArr.forEach(val => {
                    //         if (val[0] == 'December') {
                    //             if (val[1] != '-') {
                    //                 var valPerMonth = Object.entries(val[1])
                    //                 valPerMonth.forEach(valMonth => {
                    //                     // console.log(valMonth[1])
                    //                 });
                    //             }
                    //         }
                    //     });
                    //     t.row.add([element[1]['wil'], element[1]['estate'], element[1]['est'], element[1]['wil'], element[1]['wil'],element[1]['skor_bulan_January']]).draw();
                    // });
                    
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add( [ result, 32, 'Edinburgh' , result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh']).draw();
                    
                }
                })
    }

});
</script>