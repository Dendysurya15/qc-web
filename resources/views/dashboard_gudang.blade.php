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

    .Good {
        background-color: green;
        color: white;
    }

    .Satisfactory {
        background-color: yellow;
    }

    .Excellent {
        background-color: lightskyblue;
    }

    .Fair {
        background-color: orange;
    }

    .Poor {
        background-color: red
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
</style>
<div class="content-wrapper">
    <section class="content">
        <br>
        <div class="row">

            <div class="col-2">
                {{csrf_field()}}

                <select name="" class="form-control" id="regionalData">
                    <option value="1" selected>Regional I</option>
                    <option value="2">Regional II</option>
                    <option value="3">Regional III</option>
                </select>
            </div>
            <div class="col-2">

                <select name="" class="form-control" id="yearData">
                    <option value="2023" selected>2023</option>
                    <option value="2022">2022</option>
                </select>
            </div>
            <div class="col-2">
                @if (session('user_name') == 'Dennis Irawan')
                <a href="{{ route('listktu') }}" class="btn btn-success mr-2">List KTU</a>
                @endif
            </div>
        </div>
        <br>
        <div class="container-fluid">
            <div class="scroll">

                <div class="card table_wrapper">

                    <table id="tableData" class="table table-bordered text-center" class="display" style="width:100%">
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
    $(document).ready(function() {
        year = $('#yearData option:selected').val();
        regional = $('#regionalData option:selected').val();

        getData(year, regional)

    });


    $('#regionalData').change(function() {
        regional = $(this).val();
        year = $('#yearData option:selected').val();

        getData(year, regional)
    });

    $('#yearData').change(function() {
        year = $(this).val();
        regional = $('#regionalData option:selected').val();

        getData(year, regional)
    });


    function getData(year, regional) {

        var value = year;
        var value2 = regional;
        var _token = $('input[name="_token"]').val();

        $.ajax({
            url: "{{ route('getDataByYear') }}",
            method: "POST",
            data: {
                year: value,
                _token: _token,
                regional: value2
            },
            success: function(result) {
                var result = JSON.parse(result);

                // $('#tableData').dataTable().fnClearTable();
                //delete thead
                document.getElementById("tableData").deleteTHead();

                var arrHeader = Object.entries(result['arrHeader'])
                var arrMonth = Object.entries(result['arrMonth'])
                var arrCount = Object.entries(result['arrCount'])
                var arrReg = Object.entries(result['arrReg'])

                // console.log(arrCount);

                var rowHeader = '['
                for (let i = 0; i < arrHeader.length; i++) {
                    rowHeader += '"' + result['arrHeader'][i] + '",'
                }
                rowHeader = rowHeader.substring(0, rowHeader.length - 1)
                rowHeader += ']'

                var rowMonth = '['
                for (let i = 0; i < arrMonth.length; i++) {
                    rowMonth += '"' + result['arrMonth'][i] + '",'
                }
                rowMonth = rowMonth.substring(0, rowMonth.length - 1)
                rowMonth += ']'

                var rowCount = '['
                for (let i = 0; i < arrCount.length; i++) {
                    rowCount += result['arrCount'][i] + ','
                }
                rowCount = rowCount.substring(0, rowCount.length - 1)
                rowCount += ']'

                var rowReg = '['
                for (let i = 0; i < arrReg.length; i++) {
                    rowReg += result['arrReg'][i] + ','
                }
                rowReg = rowReg.substring(0, rowReg.length - 1)
                rowReg += ']'

                var parserowHeader = JSON.parse(rowHeader)
                var parserowMonth = JSON.parse(rowMonth)
                var parserowCount = JSON.parse(rowCount)
                var parserowReg = JSON.parse(rowReg)



                var thead = document.createElement('thead');
                var table = document.getElementById('tableData')


                var header = table.createTHead();
                var row = header.insertRow();
                var row2 = header.insertRow();
                var row3 = header.insertRow();

                for (let i = 0; i < 1; i++) {
                    reg = ''
                    var cell3 = row.insertCell(i);
                    if (parserowReg[0] == 1) {
                        reg = 'I'
                    } else if (parserowReg[0] == 2) {
                        reg = 'II'
                    } else if (parserowReg[0] == 3) {
                        reg = 'III'
                    }
                    cell3.innerHTML = "<b>SUMMARY SCORE GUDANG REGIONAL - " + reg + "</b>";
                    cell3.colSpan = parserowHeader.length;
                }

                let k = 1;
                for (let j = 0; j < 1; j++) {
                    var cell2 = row2.insertCell(j);
                    cell2.innerHTML = "<b>BULAN</b>";
                    cell2.colSpan = 3;
                }

                for (let i = 0; i < parserowMonth.length; i++) {
                    var cell2 = row2.insertCell(k);
                    cell2.innerHTML = "<b>" + parserowMonth[i] + "</b>";
                    cell2.colSpan = parserowCount[i] + 1;
                    cell2.style.textAlign = "center";
                    k++;
                }

                let m = 13;
                var last = parserowHeader.slice(-3);
                for (let i = 0; i < last.length; i++) {
                    var cell3 = row2.insertCell(m);
                    cell3.innerHTML = "<b>" + last[i] + "</b>";
                    cell3.rowSpan = 2;
                    cell3.style.textAlign = "center";
                    m++;
                }

                for (let i = 0; i < parserowHeader.length - 3; i++) {
                    var cell = row3.insertCell(i);
                    cell.innerHTML = "<b>" + parserowHeader[i] + "</b>";
                }


                const pagination_element = document.getElementById('pagination')
                var list_element = document.getElementById('list')

                let current_page = 1;
                let rows = 12;



                var arrResult = Object.entries(result['arrView'])
                var arrId = Object.entries(result['arrId'])
                arrResult[0][0] = "CWS";


                // console.log(arrResult);
                var tableData = document.getElementById('tableData');
                if ($("#tableData > tbody > tr").length != 0) {
                    for (let i = 0; i < arrResult.length; i++) {
                        $("#list").empty();
                    }
                }


                // arrResult.forEach(element => {
                for (let i = 0; i < arrResult.length; i++) {


                    var childArrView = Object.entries(arrResult[i][1])
                    // console.log(childArrView);
                    var childArrId = Object.entries(arrId[i][1])

                    // console.log(childArrId);
                    var tr = document.createElement('tr');
                    // console.log(childArrView[0]);
                    tr.setAttribute("id", "testing" + childArrView[0]);

                    for (let j = 0; j < childArrView.length; j++) {
                        if (childArrView[j][1] == '-') {


                            if (childArrView[j][1] == '-') {
                                var dt = '0'
                            }

                            var item_element = document.createElement('td')
                            // console.log(childArrView[j][1]);

                            item_element.innerHTML = dt
                            tr.appendChild(item_element);
                        } else if (childArrId[j][1] != '-') {
                            var item_element = document.createElement('td')
                            item_element.innerHTML = '<a href="detailInspeksi/' + childArrId[j][1] + '">' + childArrView[j][1] + ' </a>'
                            tr.appendChild(item_element);
                        } else {
                            var item = childArrView[j][1]

                            var item_element = 'item_element' + arrResult[i][0]

                            // console.log(arrResult[i][0]);
                            var item_element = document.createElement('td')

                            if (item == 'Excellent') {
                                item_element.setAttribute("class", "Excellent");
                            } else if (item == 'Good') {
                                item_element.setAttribute("class", "Good");
                            } else if (item == 'Satisfactory') {
                                item_element.setAttribute("class", "Satisfactory");
                            } else if (item == 'Fair') {
                                item_element.setAttribute("class", "Fair");
                            } else if (item == 'Poor') {
                                item_element.setAttribute("class", "Poor");
                            }

                            item_element.innerText = item
                            tr.appendChild(item_element);
                        }


                    }
                    // });
                    list_element.appendChild(tr)


                    // });
                }


            }
        })
    }
</script>