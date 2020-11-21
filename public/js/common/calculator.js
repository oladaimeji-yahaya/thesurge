$('select[name=plan]').change(function (e) {
    updateInfo(true);
});

let updateInfo = function (compute, amount) {
    let id = $('select[name=plan]').val();
    if (id) {
        let box = $('.content-box.plan');
        let week = 7;
        let month = 30;
        let year = 365;
        box.removeClass('hidden d-none');
        if (plans[id].duration % week === 0) {
            let weeks = plans[id].duration / week;
            $('.duration', box).text(weeks + ' week' + (weeks > 1 ? 's' : ''));
        } else if (plans[id].duration % month === 0) {
            let months = plans[id].duration / month;
            $('.duration', box).text(months + ' month' + (months > 1 ? 's' : ''));
        } else if (plans[id].duration % year === 0) {
            let years = plans[id].duration / year;
            $('.duration', box).text(years + ' year' + (years > 1 ? 's' : ''));
        } else {
            let days = plans[id].duration;
            $('.duration', box).text(days + ' day' + (days > 1 ? 's' : ''));
        }

        if (plans[id].incubation % week === 0) {
            let weeks = plans[id].incubation / week;
            $('.incubation', box).text(weeks + ' week' + (weeks > 1 ? 's' : ''));
        } else if (plans[id].incubation % month === 0) {
            let months = plans[id].incubation / month;
            $('.incubation', box).text(months + ' month' + (months > 1 ? 's' : ''));
        } else if (plans[id].incubation % year === 0) {
            let years = plans[id].incubation / year;
            $('.incubation', box).text(years + ' year' + (years > 1 ? 's' : ''));
        } else {
            let days = plans[id].incubation;
            $('.incubation', box).text(days + ' day' + (days > 1 ? 's' : ''));
        }

        let percentage = plans[id].rate * (plans[id].duration / plans[id].incubation);
        $('.rate', box).text(plans[id].rate + '');
        $('.compounding', box).text(plans[id].compounding + '');
        $('.trate', box).text(percentage + '');
        let max = reinvest ? Math.min(balance, plans[id].maximum) : plans[id].maximum;
        $('.amount', box).attr('placeholder', plans[id].minimum)
            .attr('min', plans[id].minimum)
            .attr('max', max);
        if (amount) {
            $('.amount', box).val(amount);
        } else {
            $('.amount', box).val(plans[id].minimum);
        }
        if (compute) {
            calculate();
        }
    }
};

let calculate = function () {
    let id = $('select[name=plan]').val();
    if (id) {
        let box = $('.content-box.plan');
        let amount, earnings;
        amount = earnings = parseFloat($('.amount', box).val());
        if (plans[id].compounding > 0) {
            for (let i = 1; i <= plans[id].duration; i++) {
                //Compound interest
                let CI = amount * (plans[id].rate / 100);
                //Current daily rate
                let percentage = CI / plans[id].incubation;
                earnings += percentage;
                //If end of interval, compound if amount > $plan->compounding
                if (i % plans[id].incubation === 0 && earnings >= plans[id].compounding) {
                    amount = earnings;
                }
            }
        } else {
            //Recalculate with new profit algo
            let rounds = plans[id].duration / plans[id].incubation;
            if (rounds > 1) {
                let interest = amount * (plans[id].rate / 100);
                let totalInterest = interest * rounds;
                earnings = amount + totalInterest;
            } else {
                let interest = amount * (plans[id].rate / 100);
                earnings = amount + interest;
            }
        }

        let formatted = earnings.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + '';
        $('.earning', box).text(formatted);
        let exchange = $('select[name=exchange] :selected');
        let rate = parseFloat(exchange.data('rate'));
        let symbol = exchange.data('symbol');
        let exchange_amt = parseFloat(amount / rate).toFixed(8);
        $('.exchange_amt', box).text(exchange_amt + ' ' + symbol);
    }
};