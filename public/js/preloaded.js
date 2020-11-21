var btcdata = function (data) {
    var list = '';
    for (var item in data) {
        list += "<span class='symbol'>&nbsp" + item + ' (' + data[item]['symbol'] + ')' + "&nbsp</span> "
                + "<span class='text'>" + data[item]['last'] + "</span> &nbsp&nbsp";
    }
    $('.btc.marquee span').html(list);

    iAjax({
        url: window.homepage + '/updateTicker/btc',
        data: {update: data},
        method: 'POST'
    });
};

var btcinfodata = function (data) {
    iAjax({
        url: window.homepage + '/updateTicker/btcinfo',
        data: {update: data},
        method: 'POST'
    });
};

var tickerdata = function (data) {
    iAjax({
        url: window.homepage + '/updateTicker/all',
        data: {update: data},
        method: 'POST'
    });
};
var txdata = function (data, section) {
    $('.bitcoin-stats .' + section).html(data);
};

$.get("/avr-withdrawn/", function (data) {
    txdata(data, 'avr-with');
});
$.get("/avr-investment/", function (data) {
    txdata(data, 'avr-inv');
});
$.get("/last-withdrawal/", function (data) {
    txdata(data, 'last-with');
});
$.get("/last-investment/", function (data) {
    txdata(data, 'last-inv');
});
if (window.updateBTC) {
    $.getJSON("https://blockchain.info/ticker", btcdata);
    $.getJSON("https://api.blockchain.info/stats", btcinfodata);
    $.getJSON("https://api.coinmarketcap.com/v1/ticker/", tickerdata);
}