function refreshSlip() {
    $.post("betslip", {}, function (data) {
        $("#betslip").animate({opacity: '0.8'});
        $("#betslip").html(data);
        $("#betslip").animate({opacity: '1'});
    }).done(function () {
        $(".loader").css("display", "none");
    });
}

function refreshBingwaFour() {
    $.post("betslip/bingwa", {}, function (data) {
        $("#betslipB").animate({opacity: '0.8'});
        $("#betslipB").html(data);
        $("#betslipB").animate({opacity: '1'});
    }).done(function () {
        $(".loader").css("display", "none");
    });
}

function addBet(value, sub_type_id, odd_key, custom, special_bet_value, bet_type, home, away, odd, oddtype, parentmatchid, pos) {
    var self = this;
    if ($('.' + custom).hasClass('picked')) {
        var counterHolder = $(".slip-counter"),
            count = counterHolder.html() * 1;
        counterHolder.html(--count);
        return removeMatch(value);
    }
    $(".loader").slideDown("slow");
    $.post("betslip/add", {
        match_id: value,
        sub_type_id: sub_type_id,
        odd_key: odd_key,
        custom: custom,
        special_bet_value: special_bet_value,
        bet_type: bet_type,
        home: home,
        away: away,
        odd: odd,
        oddtype: oddtype,
        parentmatchid: parentmatchid,
        pos: pos
    }, function (data) {
        $("." + value).removeClass('picked');
        $(self).addClass('picked');
        $("." + custom).addClass('picked');
        if (bet_type === 'jackpot') {
            refreshJackSlip();
        } else if (bet_type === 'bingwafour') {
            refreshBingwaFour();
        } else {
            refreshSlip();
            $(".slip-counter").html(data.total);
        }
    });
}

function removeMatch(value) {
    $(".loader").slideDown("slow");
    $.post("betslip/remove", {match_id: value}, function (data) {
        refreshSlip();
        $("." + value).removeClass('picked');
    });
}

function clearSlip(value) {
    $(".loader").slideDown("slow");
    $.post("betslip/clearslip", {}, function (data) {
        refreshSlip();
        $(".picked").removeClass('picked');
    });
}

function winnings() {
    var value = $("#bet_amount").val();
    var odds = $("#total_odd").val();
    var totalWin = value * odds;
    var totalWin = Math.round(totalWin);
    $("#pos_win").html(totalWin);
}


function winningsM() {
    var value = $("#bet_amount_m").val();
    var odds = $("#total_odd_m").val();
    var totalWin = value * odds;
    var totalWin = Math.round(totalWin);
    $("#pos_win_m").html(totalWin);
}
