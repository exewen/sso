$.extend({
    exportExcel: function (dataArr, fileName = '数据导出', extenson='xlsx') {
        function datenum(v, date1904) {
            if (date1904) v += 1462;
            var epoch = Date.parse(v);
            return (epoch - new Date(Date.UTC(1899, 11, 30))) / (24 * 60 * 60 * 1000);
        }

        function sheet_from_array_of_arrays(data) {
            var ws = {};
            var range = {s: {c: 10000000, r: 10000000}, e: {c: 0, r: 0}};
            for (var R = 0; R != data.length; ++R) {
                for (var C = 0; C != data[R].length; ++C) {
                    if (range.s.r > R) range.s.r = R;
                    if (range.s.c > C) range.s.c = C;
                    if (range.e.r < R) range.e.r = R;
                    if (range.e.c < C) range.e.c = C;
                    var cell = {v: data[R][C]};
                    if (cell.v == null) continue;
                    var cell_ref = XLSX.utils.encode_cell({c: C, r: R});

                    if (typeof cell.v === 'number') cell.t = 'n';
                    else if (typeof cell.v === 'boolean') cell.t = 'b';
                    else if (cell.v instanceof Date) {
                        cell.t = 'n';
                        cell.z = XLSX.SSF._table[14];
                        cell.v = datenum(cell.v);
                    }
                    else cell.t = 's';

                    ws[cell_ref] = cell;
                }
            }
            if (range.s.c < 10000000) ws['!ref'] = XLSX.utils.encode_range(range);
            return ws;
        }

        function Workbook() {
            if (!(this instanceof Workbook)) return new Workbook();
            this.SheetNames = [];
            this.Sheets = {};
        }

        function s2ab(s) {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i = 0; i != s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;
        }

        function formatJson(filterVal, jsonData) {
            var list = []
            for (var i = jsonData.length - 1; i >= 0; i--) {
                var v = jsonData[i]
                list[i] = []
                for (var j = filterVal.length - 1; j >= 0; j--) {
                    var w = filterVal[j]
                    list[i][j] = v[w]
                }
            }
            return list
        }

        var wb = new Workbook();
        for (var key in dataArr) {
            var data = dataArr[key];
            var list = formatJson(data.key, data.list)
            list.unshift(data.header)
            var ws_name = data.sheet_name !== undefined ? data.sheet_name : "Workssheet" + parseInt(key+1)
            var ws = sheet_from_array_of_arrays(list);
            wb.SheetNames.push(ws_name);
            wb.Sheets[ws_name] = ws;
        }

        var wbout = XLSX.write(wb, {bookType: 'xlsx', bookSST: false, type: 'binary'});
        var title = fileName;
        saveAs(new Blob([s2ab(wbout)], {type: "application/octet-stream"}), title + '.' + extenson)
    }
})