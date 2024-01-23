$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
function isNumberKey(evt) {
  var t = (evt.which) ? evt.which : event.keyCode;
  return !(t > 31 && (t < 48 || t > 57))
}

window.isNumberKey = function (evt) {
  var t = (evt.which) ? evt.which : event.keyCode;
  return !(t > 31 && (t < 48 || t > 57))
}
window.TestNumber = function() {
  var number = $("#customer_number").val();
  var url = $("#number_tester").val()
  // console.log(number);
  // $("#dpExist").show();
  $.ajax({
    type: "POST",
    url: url,
    data: {
      number: number,
    }, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
    // contentType: false, // The content type used when sending data to the server.
    // cache: false, // To unable request pages to be cached
    // processData: false,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    beforeSend: function () {
      // $("#loading_num2").show();
      $("#dpExist").hide();
    },
    success: function (msg) {
      // alert(msg);
      $("#dpExist").show();
      $("#dpExist").html(msg);

    }
    // }
  });
}

$("#customer_provider").click(function () {
  if ($('input#customer_provider').prop('checked')) {
    //blah blah
    $("#add_location").val('https://maps.google.com?q=0,0');
    $("#add_lat_lng").val('0,0');
    // alert("Boom Boom");

  } else {

    $("#add_location").val('');
    $("#add_location").focus();
    $("#add_lat_lng").val('');
    // alert("ZOom ZOmm");
  }

});
//
$("#c_select").on('change', function () {
  var a = $("#c_select").val();
  if (a != 'United Arab Emirates') {
    $("#sumebutton").hide();
  } else {
    $("#sumebutton").show();

  }
});
//
$('#klon1 .NumberDropDown').change(function (e) {
  // alert($(this).val());
  // alert("yes");
  var div = 'klon1 #mytypeval';
  var div2 = 'klon1 #c__select';
  // var div3 = 'klon2 #mytypeval';
  var url = $("#CheckPackageName").val();
  // var url2 = $("#CheckChannelPartner").val();
  check_package_type(div, $(this).val(), url, div2);
  // CheckChannelPartner('type', $(this).val(), url2);




  // $('#output').append('<p>' + $(this).val() + "</p>");
});
//

$('#simtype').on('change', function () {
  // alert()
  // if(this)
  var sim_type = $("#simtype").val();
  // alert(sim_type);
  if (sim_type == 'MNP' || sim_type == 'Migration') {
    $(".postpaid_package").show();
    $(".elife_package").hide();
    $(".new_package").hide();
    $("#hideme_document").show();
    $(".hideonelife").show();
    $(".itp").hide();

  } else if (sim_type == 'Elife') {
    $(".postpaid_package").hide();
    $(".new_package").hide();
    $(".elife_package").show();
    // $("#document_option").hide();
    $("#hideme_document").hide();
    $(".hideonelife").hide();
    $(".itp").hide();

  } else if (sim_type == 'New') {
    $(".hideonelife").show();
    $(".postpaid_package").hide();
    $(".new_package").show();
    $(".elife_package").hide();
    $("#hideme_document").show();
    $(".itp").hide();

  } else {
    var url = $("#ajaxUrlIT").val();
    fetch_package(sim_type, url);
    $(".itp").show();

  }
  // if (sim_type == 'MNP') {
  // alert("yes");
  //   $(".salman_ahmed").hide();
  // } else {
  //   alert("no");
  //   $(".salman_ahmed").show();
  // }
}); //
window.check_location_url = function() {
  var location = $("#add_location").val();
  var longlat = /\/\@(.*),(.*),/.exec(location);
  console.log("sabtitut");
  console.log(longlat);
  console.log(location);
  if (longlat == null) {
    $("#location_error").html("<p class='alert alert-danger'>Please use Valid Url, or Complete URL, Sample: https://www.google.com/maps/place/Little+Hut+Restaurant/@25.2778584,55.3832735,17z/data=!3m1!4b1!4m5!3m4!1s0x3e5f5c375738485f:0xe7f816caec4bca51!8m2!3d25.2778584!4d55.3854622</p>");
    $(".btn").prop("disabled", true);
    $("#checker").prop("disabled", false);
  } else {
    $("#location_error").html("<p class='alert alert-dismissible alert-success'>Thank You for Valid URL !!</p>");
    var lng = longlat['1'];
    var lat = longlat['2'];
    $("#add_lat_lng").val(lat + ',' + lng);
    $(".btn").prop("disabled", false);

    // $('#result').html('lng: ' + lng + '<br />' + 'lat: ' + lat);
  }

}
//
$('#sumebutton').click(function () {
  var numItems = $('.jackson_action').length;
  // alert(numItems);

  if (numItems == 1) {
    // alert("s");
    setTimeout(() => {
      $("#klon2 .select2-container").remove();
      $("#klon2 .NumberDropDown").empty();

    }, 100);
  } else if (numItems == 2) {
    setTimeout(() => {

      $("#klon3 .select2-container").remove();
      $("#klon3 .NumberDropDown").empty();
    }, 100);

  }
  else if (numItems == 3) {
    setTimeout(() => {

      $("#klon4 .NumberDropDown").empty();
      $("#klon4 .select2-container").remove();
    }, 100);

  }
  else if (numItems == 4) {
    setTimeout(() => {

      $("#klon5 .NumberDropDown").empty();
      $("#klon5 .select2-container").remove();
    }, 100);

  }

  // var salman_ahmed = $(".jackson_action").length();
  // var l = salman_ahmed.length;
  // alert(salman_ahmed);
  if (numItems < 5) {
    // get the last DIV which ID starts with ^= "klon"
    var $div = $('div[id^="klon"]:last');
    // Read the Number from that DIV's ID (i.e: 3 from "klon3")
    // And increment that number by 1
    var num = parseInt($div.prop("id").match(/\d+/g), 10) + 1;
    // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
    var $klon = $div.clone(true).prop('id', 'klon' + num);
    // $klon.find(".NumberDropDown").each(function (index) {
    //     $(this).select2('destroy');
    // });
    var jackson_action = $(".jackson_action").html();
    // var salmanahmed = 'salmanahmed';

    // Finally insert $klon wherever you want
    $div.after($klon.html(jackson_action));
    $("#klon2 #mytypeval").change(function () {
      var id = $("#klon2 #mytypeval").val();
      var url = $("#AjaxUrl2").val();
      // alert(id);
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: {
          id: id,
        },
        url: url,
        cache: false,
        beforeSend: function () {
          $("#klon2 #c__select").empty();
        },
        success: function (res) {
          // location.reload();
          if (res) {
            $('#klon2 #c__select').append($("<option/>", {
              value: '',
              text: 'Select'
            }));
            $.each(res, function (key, value) {
              $('#klon2 #c__select').append($("<option/>", {
                value: key,
                text: value
              }));
            });
          }
          // var value = $.trim(value);
          // $("#fetch_teacher").html(value);
        }
      });
      //
      console.log("DONE CHANGE KRO");
      setTimeout(() => {
        if ($('select').data('select2')) {
          $('select').select2('destroy');
        }
        // $(".NumberDropDown").select2("destroy");
        $('#klon2 .NumberDropDown').select2({
          placeholder: 'Please Search Numbers',
          // allowclear
          allowClear: true,
          // dropdownParent: $('#AddSkill'),
          // tags: true,
          minimumInputLength: 2,
          ajax: {
            url: '/skill-auto-complete?id=' + $("#klon2 #mytypeval").val() + '&pid=' + $("#type").val(),
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
              return {
                results: $.map(data, function (item) {
                  return {
                    text: item.number,
                    id: item.number
                  }
                })
              };

            }
          }
        });
      }, 1000);
      $('#klon2 .NumberDropDown').change(function (e) {
        // alert($(this).val());
        // alert("yes");
        console.log("ssssss");
        var div = 'klon2 #mytypeval';
        var div2 = 'klon2 #c__select';
        var url = $("#CheckPackageName").val();
        // check_package_type(div, $(this).val(), url,div2);
        // $('#output').append('<p>' + $(this).val() + "</p>");
      });
    });
    $("#klon3 #mytypeval").change(function () {
      var id = $("#klon3 #mytypeval").val();
      var url = $("#AjaxUrl2").val();
      // alert(id);
      //
      $("#klon3 .NumberDropDown").empty();
      $("#klon3 #lm").val('');
      $("#klon3 #fm").val('');
      $("#klon3 #data").val('');
      $("#klon3 #pnum").val('');
      $("#klon3 #fmnum").val('');
      $("#klon3 #mp1").val('');
      $("#klon3 #contract_commitment_1").text('');
      //
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: {
          id: id,
        },
        url: url,
        cache: false,
        beforeSend: function () {
          $("#klon3 #c__select").empty();
        },
        success: function (res) {
          // location.reload();
          if (res) {
            $('#klon3 #c__select').append($("<option/>", {
              value: '',
              text: 'Select'
            }));
            $.each(res, function (key, value) {
              $('#klon3 #c__select').append($("<option/>", {
                value: key,
                text: value
              }));
            });
          }
          // var value = $.trim(value);
          // $("#fetch_teacher").html(value);
        }
      });
      //
      setTimeout(() => {
        $('#klon3 .NumberDropDown').select2({
          placeholder: 'Please Search Numbers',
          // dropdownParent: $('#AddSkill'),
          // tags: true,
          minimumInputLength: 2,
          ajax: {
            url: '/skill-auto-complete?id=' + $("#klon3 #mytypeval").val() + '&pid=' + $("#type").val(),
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
              return {
                results: $.map(data, function (item) {
                  return {
                    text: item.number,
                    id: item.number
                  }
                })
              };

            }
          }
        });
      }, 1000);
    });
    $("#klon4 #mytypeval").change(function () {
      var id = $("#klon4 #mytypeval").val();
      var url = $("#AjaxUrl2").val();
      // alert(id);
      //
      $("#klon4 .NumberDropDown").empty();
      $("#klon4 #lm").val('');
      $("#klon4 #fm").val('');
      $("#klon4 #data").val('');
      $("#klon4 #pnum").val('');
      $("#klon4 #fmnum").val('');
      $("#klon4 #mp1").val('');
      $("#klon4 #contract_commitment_1").text('');
      //
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: {
          id: id,
        },
        url: url,
        cache: false,
        beforeSend: function () {
          $("#klon4 #c__select").empty();
        },
        success: function (res) {
          // location.reload();
          if (res) {
            $('#klon4 #c__select').append($("<option/>", {
              value: '',
              text: 'Select'
            }));
            $.each(res, function (key, value) {
              $('#klon4 #c__select').append($("<option/>", {
                value: key,
                text: value
              }));
            });
          }
          // var value = $.trim(value);
          // $("#fetch_teacher").html(value);
        }
      });
      //
      setTimeout(() => {
        $('#klon4 .NumberDropDown').select2({
          placeholder: 'Please Search Numbers',
          // dropdownParent: $('#AddSkill'),
          // tags: true,
          minimumInputLength: 2,
          ajax: {
            url: '/skill-auto-complete?id=' + $("#klon4 #mytypeval").val() + '&pid=' + $("#type").val(),
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
              return {
                results: $.map(data, function (item) {
                  return {
                    text: item.number,
                    id: item.number
                  }
                })
              };

            }
          }
        });
      }, 1000);
    });
    $("#klon5 #mytypeval").change(function () {
      var id = $("#klon5 #mytypeval").val();
      var url = $("#AjaxUrl2").val();
      // alert(id);
      //
      $("#klon5 .NumberDropDown").empty();
      $("#klon5 #lm").val('');
      $("#klon5 #fm").val('');
      $("#klon5 #data").val('');
      $("#klon5 #pnum").val('');
      $("#klon5 #fmnum").val('');
      $("#klon5 #mp1").val('');
      $("#klon5 #contract_commitment_1").text('');
      //
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: {
          id: id,
        },
        url: url,
        cache: false,
        beforeSend: function () {
          $("#klon5 #c__select").empty();
        },
        success: function (res) {
          // location.reload();
          if (res) {
            $('#klon5 #c__select').append($("<option/>", {
              value: '',
              text: 'Select'
            }));
            $.each(res, function (key, value) {
              $('#klon5 #c__select').append($("<option/>", {
                value: key,
                text: value
              }));
            });
          }
          // var value = $.trim(value);
          // $("#fetch_teacher").html(value);
        }
      });
      //
      setTimeout(() => {
        $('#klon5 .NumberDropDown').select2({
          placeholder: 'Please Search Numbers',
          // dropdownParent: $('#AddSkill'),
          // tags: true,
          minimumInputLength: 2,
          ajax: {
            url: '/skill-auto-complete?id=' + $("#klon5 #mytypeval").val() + '&pid=' + $("#type").val(),
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
              return {
                results: $.map(data, function (item) {
                  return {
                    text: item.number,
                    id: item.number
                  }
                })
              };

            }
          }
        });
      }, 1000);
    });

  }

});

$("#klon1 .c__select").on('change', function () {
  // alert("yes");
  var plan_name = $(this).val();
  var AjaxUrl = $("#AjaxUrl").val();
  $.ajax({
    // e.preventDefault();
    type: "POST",
    url: AjaxUrl,
    data: {
      'ajaxName': 'PlanFetch',
      'plan_name': plan_name,
      // 'housess': housess
    },
    // data: {plan_name: plan_name},
    beforeSend: function () {
      //   $("div#divLoading").addClass('show');
      // $("#quick_search_text").css("background", "#FFF url(include/ajax/loading.gif) no-repeat 165px");
    },
    success: function (value) {

      var a = JSON.stringify(value);
      // alert(a);
      console.log(a);
      // var b =
      var obj = $.parseJSON(a);
      // console.log(obj['0']['flexible_minutes']);

      // alert(a[1]['plan_name']);

      // var data = value.split(",");
      // // alert(data[0]);
      $("#klon1 .fname1").val(data[0]);
      $("#klon1 .contract_commitment_1").text(obj['0']['duration']);
      $('#klon1 .lm').val(obj['0']['local_minutes']);
      $('#klon1 .fm').val(obj['0']['flexible_minutes']);
      $('#klon1 .samina').val(obj['0']['data']);
      $('#klon1 .pnum').val(obj['0']['number_allowed']);
      $('#klon1 .fmnum').val(obj['0']['free_minutes']);
      $('#klon1 .mp').val(obj['0']['monthly_payment']);
      // $("#suggesstion-box2").show();
      // $("#suggesstion-box2").html(data);
      // $("#quick_search_text").css("background", "#FFF");
    }
  });
  // alert("1" + k);
});
// $('body').on('click', '#klon2', function() {
// do something
// alert("damn");
// });
// $("#klon1").click(function(){
// })

$("body").on('change', '#klon2 .c__select', function () {
  // alert("yes");
  // var k = $(this).val();
  // alert(k);
  var plan_name = $(this).val();
  var AjaxUrl = $("#AjaxUrl").val();
  // alert(plan_name);
  $.ajax({
    // e.preventDefault();
    type: "POST",
    url: AjaxUrl,
    data: {
      'ajaxName': 'PlanFetch',
      'plan_name': plan_name,
      // 'housess': housess
    },
    // data: {plan_name: plan_name},
    beforeSend: function () {
      //   $("div#divLoading").addClass('show');

      // $("#quick_search_text").css("background", "#FFF url(include/ajax/loading.gif) no-repeat 165px");
    },
    success: function (value) {
      var a = JSON.stringify(value);
      // alert(a);
      console.log(a);
      // var b =
      var obj = $.parseJSON(a);
      // console.log(obj['0']['flexible_minutes']);

      // alert(a[1]['plan_name']);

      // var data = value.split(",");
      // // alert(data[0]);
      $("#klon2 .fname1").val(data[0]);
      $("#klon2 .contract_commitment_1").text(obj['0']['duration']);
      $('#klon2 .lm').val(obj['0']['local_minutes']);
      $('#klon2 .fm').val(obj['0']['flexible_minutes']);
      $('#klon2 .samina').val(obj['0']['data']);
      $('#klon2 .pnum').val(obj['0']['number_allowed']);
      $('#klon2 .fmnum').val(obj['0']['free_minutes']);
      $('#klon2 .mp').val(obj['0']['monthly_payment']);
      // $("#suggesstion-box2").show();
      // $("#suggesstion-box2").html(data);
      // $("#quick_search_text").css("background", "#FFF");
    }
  });
});
$("body").on('change', '#klon3 .c__select', function () {
  // alert("yes");
  // var k = $(this).val();
  // alert(k);
  var plan_name = $(this).val();
  var AjaxUrl = $("#AjaxUrl").val();
  // alert(plan_name);
  $.ajax({
    // e.preventDefault();
    type: "POST",
    url: AjaxUrl,
    data: {
      'ajaxName': 'PlanFetch',
      'plan_name': plan_name,
      // 'housess': housess
    },
    // data: {plan_name: plan_name},
    beforeSend: function () {
      //   $("div#divLoading").addClass('show');

      // $("#quick_search_text").css("background", "#FFF url(include/ajax/loading.gif) no-repeat 165px");
    },
    success: function (value) {
      var a = JSON.stringify(value);
      // alert(a);
      console.log(a);
      // var b =
      var obj = $.parseJSON(a);
      // console.log(obj['0']['flexible_minutes']);

      // alert(a[1]['plan_name']);

      // var data = value.split(",");
      // // alert(data[0]);
      $("#klon3 .fname1").val(data[0]);
      $("#klon3 .contract_commitment_1").text(obj['0']['duration']);
      $('#klon3 .lm').val(obj['0']['local_minutes']);
      $('#klon3 .fm').val(obj['0']['flexible_minutes']);
      $('#klon3 .samina').val(obj['0']['data']);
      $('#klon3 .pnum').val(obj['0']['number_allowed']);
      $('#klon3 .fmnum').val(obj['0']['free_minutes']);
      $('#klon3 .mp').val(obj['0']['monthly_payment']);
      // $("#suggesstion-box2").show();
      // $("#suggesstion-box2").html(data);
      // $("#quick_search_text").css("background", "#FFF");
    }
  });
});
$("body").on('change', '#klon4 .c__select', function () {
  // alert("yes");
  // var k = $(this).val();
  // alert(k);
  var plan_name = $(this).val();
  var AjaxUrl = $("#AjaxUrl").val();
  // alert(plan_name);
  $.ajax({
    // e.preventDefault();
    type: "POST",
    url: AjaxUrl,
    data: {
      'ajaxName': 'PlanFetch',
      'plan_name': plan_name,
      // 'housess': housess
    },
    // data: {plan_name: plan_name},
    beforeSend: function () {
      //   $("div#divLoading").addClass('show');

      // $("#quick_search_text").css("background", "#FFF url(include/ajax/loading.gif) no-repeat 165px");
    },
    success: function (value) {
      var a = JSON.stringify(value);
      // alert(a);
      console.log(a);
      // var b =
      var obj = $.parseJSON(a);
      // console.log(obj['0']['flexible_minutes']);

      // alert(a[1]['plan_name']);

      // var data = value.split(",");
      // // alert(data[0]);
      $("#klon4 .fname1").val(data[0]);
      $("#klon4 .contract_commitment_1").text(obj['0']['duration']);
      $('#klon4 .lm').val(obj['0']['local_minutes']);
      $('#klon4 .fm').val(obj['0']['flexible_minutes']);
      $('#klon4 .samina').val(obj['0']['data']);
      $('#klon4 .pnum').val(obj['0']['number_allowed']);
      $('#klon4 .fmnum').val(obj['0']['free_minutes']);
      $('#klon4 .mp').val(obj['0']['monthly_payment']);
      // $("#suggesstion-box2").show();
      // $("#suggesstion-box2").html(data);
      // $("#quick_search_text").css("background", "#FFF");
    }
  });
});
$("body").on('change', '#klon5 .c__select', function () {
  // alert("yes");
  // var k = $(this).val();
  // alert(k);
  var plan_name = $(this).val();
  var AjaxUrl = $("#AjaxUrl").val();
  // alert(plan_name);
  $.ajax({
    // e.preventDefault();
    type: "POST",
    url: AjaxUrl,
    data: {
      'ajaxName': 'PlanFetch',
      'plan_name': plan_name,
      // 'housess': housess
    },
    // data: {plan_name: plan_name},
    beforeSend: function () {
      //   $("div#divLoading").addClass('show');

      // $("#quick_search_text").css("background", "#FFF url(include/ajax/loading.gif) no-repeat 165px");
    },
    success: function (value) {
      var a = JSON.stringify(value);
      // alert(a);
      console.log(a);
      // var b =
      var obj = $.parseJSON(a);
      // console.log(obj['0']['flexible_minutes']);

      // alert(a[1]['plan_name']);

      // var data = value.split(",");
      // // alert(data[0]);
      $("#klon5 .fname1").val(data[0]);
      $("#klon5 .contract_commitment_1").text(obj['0']['duration']);
      $('#klon5 .lm').val(obj['0']['local_minutes']);
      $('#klon5 .fm').val(obj['0']['flexible_minutes']);
      $('#klon5 .samina').val(obj['0']['data']);
      $('#klon5 .pnum').val(obj['0']['number_allowed']);
      $('#klon5 .fmnum').val(obj['0']['free_minutes']);
      $('#klon5 .mp').val(obj['0']['monthly_payment']);
      // $("#suggesstion-box2").show();
      // $("#suggesstion-box2").html(data);
      // $("#quick_search_text").css("background", "#FFF");
    }
  });
});

$("#klon1 #mytypeval").change(function () {
  //
  var ac = $(this).val();
  // var
  // alert(ac);
  if (ac == 'my') {

  } else {

    var url3 = $("#AjaxUrl3").val();
    // alert(url3);
    checkNumData(url3);
  }
  //
  var id = $("#klon1 #mytypeval").val();
  var url = $("#AjaxUrl2").val();
  // alert(id);
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: 'POST',
    data: {
      id: id,
    },
    url: url,
    cache: false,
    beforeSend: function () {
      $("#klon1 #c__select").empty();
    },
    success: function (res) {
      // location.reload();
      if (res) {
        $('#klon1 #c__select').append($("<option/>", {
          value: '',
          text: 'Select'
        }));
        $.each(res, function (key, value) {
          $('#klon1 #c__select').append($("<option/>", {
            value: key,
            text: value
          }));
        });
      }
      // var value = $.trim(value);
      // $("#fetch_teacher").html(value);
    }
  });
  $("#klon1 #mytypeval").change(function () {
    $("#klon1 .NumberDropDown").empty();
    $("#klon1 #lm").val('');
    $("#klon1 #fm").val('');
    $("#klon1 #data").val('');
    $("#klon1 #pnum").val('');
    $("#klon1 #fmnum").val('');
    $("#klon1 #mp1").val('');
    $("#klon1 #contract_commitment_1").text('');
  })
  //
  $('#klon1 .NumberDropDown').select2({
    placeholder: 'Please Search Numbers',
    // dropdownParent: $('#AddSkill'),
    // tags: true,
    minimumInputLength: 2,
    ajax: {
      url: '/skill-auto-complete-multi-channel?id=' + $("#klon1 #mytypeval").val(),
      // url: '/skill-auto-complete?id=' + $("#klon1 #mytypeval").val() + '&pid=' + $("#type").val(),
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results: $.map(data, function (item) {
            return {
              text: item.number,
              id: item.number
            }
          })
        };

      }
    }
  });
});
// $("#klon2 #mytypeval").change(function () {
//   //
//   $("#klon2 .NumberDropDown").empty();
//   $("#klon2 #lm").val('');
//   $("#klon2 #fm").val('');
//   $("#klon2 #data").val('');
//   $("#klon2 #pnum").val('');
//   $("#klon2 #fmnum").val('');
//   $("#klon2 #mp1").val('');
//   $("#klon2 #contract_commitment_1").text('');
//   //
//   console.log("DONE CHANGE KRO");
//   setTimeout(() => {
//     $('#klon2 .NumberDropDown').select2({
//       placeholder: 'Please Search Numbers',
//       // dropdownParent: $('#AddSkill'),
//       // tags: true,
//       minimumInputLength: 2,
//       ajax: {
//         url: '/skill-auto-complete?id=' + $("#klon2 #mytypeval").val() + '&pid=' + $("#type").val(),
//         dataType: 'json',
//         delay: 250,
//         processResults: function (data) {
//           return {
//             results: $.map(data, function (item) {
//               return {
//                 text: item.number,
//                 id: item.number
//               }
//             })
//           };

//         }
//       }
//     });
//   }, 1000);
// });


window.checkNumData = function(url) {
  // alert(div);
  // alert(number);
  var number = '';
  // alert(url);
  $.ajax({
    type: 'POST',
    url: url,
    data: {
      number: number,
    },
    success: function (data) {
      // alert(data);
      var num_limit = 100;
      if (data >= num_limit) {
        Swal.fire(
          'Alert!',
          'You have exceeded your number limit of ' + num_limit + ', Please choose Resevered Number List!',
          'error'
        )
        $("#number_exceed_msg").html('<p style="color:red;">You have exceeded your number limit of ' + num_limit + ', Please choose from your Resevered Number List</p>')
        // alert("You already Choose Limited Number, Please use Reserved Number Thanks");
        // $("")
      }
      // console.log(div);
      // $("#"+div).val(data);
      // check_package(data);
      // $("#ReportingData").html(data);
    }
  });
}

window.check_package = function(id, div) {
  // $("#klon1 #mytypeval").change(function () {
  // var div
  // console.log(div);
  var country = $("#c_select").val();

  // var id = $("#klon1 #mytypeval").val();
  var url = $("#AjaxUrl2").val();
  // alert(id);
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: 'POST',
    data: {
      id: id,
      country: country,
    },
    url: url,
    cache: false,
    beforeSend: function () {
      // $("#klon1 #c__select").empty();
      $("#" + div).empty();
    },
    success: function (res) {
      // location.reload();
      if (res) {
        $("#" + div).append($("<option/>", {
          value: '',
          text: 'Select'
        }));
        $.each(res, function (key, value) {
          $("#" + div).append($("<option/>", {
            value: key,
            text: value
          }));
        });
        // $('#klon2 #c__select').append($("<option/>", {
        //     value: '',
        //     text: 'Select'
        // }));
        // $.each(res, function (key, value) {
        //     $('#klon2 #c__select').append($("<option/>", {
        //         value: key,
        //         text: value
        //     }));
        // });
      }
      // var value = $.trim(value);
      // $("#fetch_teacher").html(value);
    }
  });
  // $("#klon1 #mytypeval").change(function () {
  //   $("#klon1 .NumberDropDown").empty();
  //   $("#klon1 #lm").val('');
  //   $("#klon1 #fm").val('');
  //   $("#klon1 #data").val('');
  //   $("#klon1 #pnum").val('');
  //   $("#klon1 #fmnum").val('');
  //   $("#klon1 #mp1").val('');
  //   $("#klon1 #contract_commitment_1").text('');
  // })
  //
  //     $('#klon1 .NumberDropDown').select2({
  //         placeholder: 'Please Search Numbers',
  //         // dropdownParent: $('#AddSkill'),
  //         // tags: true,
  //         minimumInputLength: 2,
  //         ajax: {
  //             url: '/skill-auto-complete?id=' + $("#klon1 #mytypeval").val() + '&pid=' + $("#type").val(),
  //             dataType: 'json',
  //             delay: 250,
  //             processResults: function (data) {
  //                 return {
  //                     results: $.map(data, function (item) {
  //                         return {
  //                             text: item.number,
  //                             id: item.number
  //                         }
  //                     })
  //                 };

  //             }
  //         }
  //     });
  // });
}

window.check_package_type = function(div, number, url, div2) {
  // alert(div);
  // alert(number);
  // alert(url);
  $.ajax({
    type: 'POST',
    url: url,
    data: {
      number: number,
    },
    success: function (data) {
      // alert(data);
      console.log(div);
      $("#" + div).val(data);
      check_package(data, div2);
      // $("#ReportingData").html(data);
    }
  });
}

window.CheckChannelPartner = function(div, number, url) {
  // alert(div);
  // alert(number);
  // alert(url);
  $.ajax({
    type: 'POST',
    url: url,
    data: {
      number: number,
    },
    success: function (data) {
      // alert(data);
      console.log(div);
      $("#" + div).val($.trim(data));
      // check_package(data,div2);
      // $("#ReportingData").html(data);
    }
  });
}


//
//
window.SavingActivationLead = function(url, form, redirect) {
  if (confirm("are you sure all information accurate, Kindly make sure before proceed?")) {
    // console.log("Accepted")
    // } else {
    // console.log("Declined")
    var rizwan = document.getElementById(form);
    $.ajax({
      type: "POST",
      url: url,
      data: new FormData(rizwan), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
      contentType: false, // The content type used when sending data to the server.
      cache: false, // To unable request pages to be cached
      processData: false,
      beforeSend: function () {
        $("#loading_num3").show();
        // // $(".request_call").hide();
        $('.btn').prop('disabled', true);
        // $('#' + btn).prop('disabled', true);

      },
      success: function (data) {
        // alert(data);
        if ($.isEmptyObject(data.error)) {
          alert(data.success);
          $("#loading_num3").hide();
          $('.btn').prop('disabled', false);
          // window.location.href = 'https://soft.riuman.com/admin/activation'
          window.location.href = redirect;
        } else {
          $('.btn').prop('disabled', false);
          // alert("S");
          $("#loading_num3").hide();

          printErrorMsg(data.error);
        }
      },
      error: function (data) {
        printErrorMsg(data.responseJSON);

        // alert(data.responseJSON);
        // console.log(data.responseJSON);
        // alert("fail");
      }

    });
  }

}
//

window.printErrorMsg = function(msg) {
  $(".print-error-msg").find("ul").html('');
  $(".print-error-msg").css('display', 'block');
  $.each(msg, function (key, value) {
    $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
  });
}

let tooltipelements = document.querySelectorAll("[data-bs-toggle='tooltip']");
tooltipelements.forEach((el) => {
  new bootstrap.Tooltip(el);
});

window.MyLead= function(url, status, loadingUrl) {
  $.ajax({
    type: 'POST',
    url: url,
    data: {
      status: status
    },
    beforeSend: function () {
      $("#LoadSalerData").html('<img src="' + loadingUrl + '" class="img-fluid text-center offset-md-6" style="width:35px;"></img>');
      // $("#loading_num3").html('<p> Loading </p>');
    },
    success: function (data) {
      // alert(data);
      // $("#loading_num3").hide();
      $("#LoadSalerData").html(data);
    }
  });
}

window.NumberDtl = function(simtype, url, partner) {
  $.ajax({
    type: 'POST',
    url: url,
    data: {
      simtype: simtype,
      partner: partner,
    },
    beforeSend: function () {
      $("#loading_num").show();
      // // $(".request_call").hide();
      // $('#' + btn).prop('disabled', true);
    },
    success: function (data) {
      // alert(data);
      $("#loading_num").hide();
      $("#broom").html(data);
    }
  });
}
//
window.myconversation = function() {
  // alert($(".chat_input").val());
  var remarks = $(".chat_input").val();
  var id = $("#leadid").val();
  var url = $("#ChatAjaxUrl").val();
  var saler_id = $("#saler_id").val();
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: 'POST',
    data: {
      id: id,
      remarks: remarks,
      saler_id: saler_id,
    },
    url: url,
    cache: false,
    beforeSend: function () {
      $(".chat_input").val('');
      // $(".chat_input").empty();
    },
    success: function (res) {
      // alert(res);
      // console.log(res);
      var data = $.trim(res);
      // alert(data);
      // $("#leadno").text(data);
      $(".msg_container_base").html(data);
      // $(".msg_sent").text('<p>Lorem ipsum</p>');
      // location.reload();
      // if (res) {
      //     $('#package_id').append($("<option/>", {
      //         value: '',
      //         text: 'Select'
      //     }));
      //     $.each(res, function (key, value) {

      //         $('#package_id').append($("<option/>", {
      //             value: key,
      //             text: value
      //         }));
      //     });
      // }
      // var value = $.trim(value);
      // $("#fetch_teacher").html(value);
    }
  });
}

window.accept_lead = function(lead_id, url, web, version) {
  // alert(div);
  // alert(number);
  // alert(url);
  // if (confirm("are you sure you want to accept this lead?")){
  // let url = {{route('admin')}}
  $.ajax({
    type: 'POST',
    url: url,
    data: {
      lead_id: lead_id,
    },
    beforeSend: function () {
      // $("#klon1 #c__select").empty();
      // $("#" + div).empty();
      // Swal.fire(
      //     'Processing!',
      //     'Please wait, we are checking, are you the real attender?',
      //     'success'
      // )
    },
    success: function (data) {
      // alert(data);
      setTimeout(() => {
        if (data > 0) {
          window.location.href = web + '/' + lead_id
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Lead already in process',
            text: 'Please try New Lead',
            //  footer: '<a href>Why do I have this issue?</a>'
          })
          // alert("lead already in process, Please try New Lead");
        }
      }, 0);
      // console.log(div);
      // $("#"+div).val(data);
      // check_package(data);
      // $("#ReportingData").html(data);
    }
  });
  // }
}

window.accept_lead_force = function(lead_id, url, web, version) {
  // alert(div);
  // alert(number);
  // alert(url);
  if (confirm("are you sure you want to accept this lead?")) {
    // let url = {{route('admin')}}
    $.ajax({
      type: 'POST',
      url: url,
      data: {
        lead_id: lead_id,
      },
      beforeSend: function () {
        // $("#klon1 #c__select").empty();
        // $("#" + div).empty();
        // Swal.fire(
        //     'Processing!',
        //     'Please wait, we are checking, are you the real attender?',
        //     'success'
        // )
      },
      success: function (data) {
        // alert(data);
        setTimeout(() => {
          if (data > 0) {
            window.location.href = web + '/' + lead_id
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Lead already in process',
              text: 'Please try New Lead',
              //  footer: '<a href>Why do I have this issue?</a>'
            })
            // alert("lead already in process, Please try New Lead");
          }
        }, 0);
        // console.log(div);
        // $("#"+div).val(data);
        // check_package(data);
        // $("#ReportingData").html(data);
      }
    });
  }
}

//
$(".box:checked").next().addClass("blue");


//
$('#state').change(function () {
  $('#province').attr('disabled', this.value == "other1");
});

$("#state2").change(function () {
  $("#province2").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});

$("#state3").change(function () {
  $("#province3").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state_gender").change(function () {
  //   alert("s");
  $("#p_gender3").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state4").change(function () {
  $("#province4").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state_emirates").change(function () {
  // alert("s");
  $("#province_emirates").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state_area").change(function () {
  // alert("s");
  $("#state__area").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state_language").change(function () {
  // alert("s");
  $("#province_language").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});

$("#state5").change(function () {
  $("#province5").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state_emirate_num").change(function () {
  $("#province_original_id1").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state6").change(function () {
  $("#province6").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state7").change(function () {
  $("#province7").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state8").change(function () {
  $("#province8").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});

$("#state9").change(function () {
  $("#province9").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});

$("#state10").change(function () {
  $("#province10").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state11").change(function () {
  $("#province11").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});

$("#state12").change(function () {
  $("#province12").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state13").change(function () {
  $("#province13").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});

$("#state14").change(function () {
  $("#province14").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});

$("#device_duration_state").change(function () {
  $("#device_duration").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});

$("#state15").change(function () {
  $("#province15").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state16").change(function () {
  $("#province16").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});

$("#state17").change(function () {
  $("#province17").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state18").change(function () {
  $("#province18").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});
$("#state19").change(function () {
  $("#province19").attr("disabled", this.value == "other1");
  // or $("#flap-drop").toggle(this.value!="23");
});

$(document).ready(function () {
$.fn.modal.Constructor.prototype._enforceFocus = function () {};
// when modal is open
$('.modal').on('shown.bs.modal', function () {
  $('#select2-sample').select2({
    // ....
  });
});

  var pathname = window.location.pathname; // Returns path only (/path/example.html)
  // alert(pathname);
  if (pathname.indexOf("verification") >= 0) {



    $("#klon1").find('input, textarea, button, select').attr('disabled', 'disabled');
    // $("#klon2").find('input, textarea, button, select').attr('disabled', 'disabled');
    $("#klon2").find('input, textarea, button, select').attr('disabled', 'disabled');
    $("#klon3").find('input, textarea, button, select').attr('disabled', 'disabled');
    $("#klon4").find('input, textarea, button, select').attr('disabled', 'disabled');
    $("#klon5").find('input, textarea, button, select').attr('disabled', 'disabled');
    $(".klon_verify").attr('disabled', false);
    $(".klon_verify").prop('disabled', false);
  } else if (pathname.indexOf("admin/lead") >= 0) {
    var t = $("#simtype").val();
    var url = $("#ajaxUrlIT").val();

    console.log(t);
    if (t == 'MNP' || t == 'Migration') {
      var url2 = $("#ajaxUrlMNP").val();
      plan_month($(".plan_mnp").val(), 'PlanFetch', url2);
    } else {
      fetch_package(t, url);
      setTimeout(() => {
        itplan($("#itplanid").val(), $("#itplanurl").val());
        $("#package_id").val($("#itplanid").val());
      }, 1000);
    }
  }
  $('#province').change(function () {
    $('#province1').val($('#province').val());
  });
  $('#state__area').change(function () {
    $('#state_area_hidden').val($('#state__area').val());
  });

  $('#province2').change(function () {
    $('#province22').val($('#province2').val());
  });

  $('#p_gender3').change(function () {
    $('#p_gender').val($('#p_gender3').val());
  });
  $('#province4').change(function () {
    $('#province44').val($('#province4').val());
  });
  $('#province3').change(function () {
    $('#province33').val($('#province4').val());
  });
  $('#province_original_id1').change(function () {
    $('#province_original_id11').val($('#province_original_id1').val());
  });
  $('#province5').change(function () {
    $('#province55').val($('#province5').val());
  });
  $('#province6').change(function () {
    $('#province66').val($('#province6').val());
  });
  $('#province7').change(function () {
    $('#province77').val($('#province7').val());
  });
  $('#province8').change(function () {
    $('#province88').val($('#province8').val());
  });
  $('#province9').change(function () {
    $('#province99').val($('#province9').val());
  });
  $('#province10').change(function () {
    $('#province100').val($('#province10').val());
  });
  $('#province11').change(function () {
    $('#province111').val($('#province11').val());
  });
  $('#province12').change(function () {
    $('#province112').val($('#province12').val());
  });
  $('#province13').change(function () {
    $('#province113').val($('#province13').val());
  });
  $('#province14').change(function () {
    $('#province114').val($('#province14').val());
  });
  $('#province15').change(function () {
    $('#province115').val($('#province15').val());
  });
  $('#province16').change(function () {
    $('#province116').val($('#province16').val());
  });
  $('#province17').change(function () {
    $('#province117').val($('#province17').val());
  });
  $('#province_emirates').change(function () {
    $('#province__emirates').val($('#province_emirates').val());
  });
  $('#province_language').change(function () {
    $('#province__language').val($('#province_language').val());
  });
  $('#province18').change(function () {
    $('#province118').val($('#province18').val());
  });
  $('#province19').change(function () {
    $('#province119').val($('#province19').val());
  });

  $('#province_local_minutes').change(function () {
    $('#province_local_minutes1').val($('#province_local_minutes').val());
  });

  $('#preffered_number_allowed').change(function () {
    $('#preffered_number_allowed1').val($('#preffered_number_allowed').val());
  });


  $('#free_minutes').change(function () {
    $('#free_minutes1').val($('#free_minutes').val());
  });

  $('#mobile_payment').change(function () {
    $('#mobile_payment1').val($('#mobile_payment').val());
  });

  $('#device_duration').change(function () {
    $('#device_duration1').val($('#device_duration').val());
  });
  $("#show_sumebutton").click(function () {
    //   $("#show_sumebutton").hide();
    //   $("#sumebutton").show();
  });
});

$(document).on('click', '.panel-heading span.icon_minim', function (e) {
  var $this = $(this);
  if (!$this.hasClass('panel-collapsed')) {
    $this.parents('.panel').find('.panel-body').slideUp();
    $this.addClass('panel-collapsed');
    $this.removeClass('glyphicon-minus').addClass('glyphicon-plus');
  } else {
    $this.parents('.panel').find('.panel-body').slideDown();
    $this.removeClass('panel-collapsed');
    $this.removeClass('glyphicon-plus').addClass('glyphicon-minus');
  }
});
$(document).on('focus', '.panel-footer input.chat_input', function (e) {
  var $this = $(this);
  if ($('#minim_chat_window').hasClass('panel-collapsed')) {
    $this.parents('.panel').find('.panel-body').slideDown();
    $('#minim_chat_window').removeClass('panel-collapsed');
    $('#minim_chat_window').removeClass('glyphicon-plus').addClass('glyphicon-minus');
  }
});
$(document).on('click', '#new_chat', function (e) {
  var size = $(".chat-window:last-child").css("margin-left");
  size_total = parseInt(size) + 400;
  alert(size_total);
  var clone = $("#chat_window_1").clone().appendTo(".container");
  clone.css("margin-left", size_total);
});
$(document).on('click', '.icon_close', function (e) {
  //$(this).parent().parent().parent().parent().remove();
  $("#chat_window_1").remove();
});
// $('.myDatepicker').bootstrapMaterialDatePicker({
//   format: 'YYYY-MM-DD hh:mm:ss'
// });
// $('.myDatepicker2').bootstrapMaterialDatePicker({
//   format: 'YYYY-MM-DD hh:mm:ss'
// });
$(document).ready(function () {
  // $('#pdf').DataTable({
  //     dom: 'Bfrtip',
  //     buttons: [
  //         'copy', 'csv', 'excel', 'pdf', 'print'
  //     ]
  // });
});


window.VerifyLead = function (url, form, redirect) {
  var rizwan = document.getElementById(form);
  $.ajax({
    type: "POST",
    url: url,
    data: new FormData(rizwan), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
    contentType: false, // The content type used when sending data to the server.
    cache: false, // To unable request pages to be cached
    processData: false,
    beforeSend: function () {
      $("#loading_num3").show();
      // // $(".request_call").hide();
      $('.btn').prop('disabled', true);
      // $('#' + btn).prop('disabled', true);

    },
    success: function (data) {
      // alert(data);
      if ($.isEmptyObject(data.error)) {
        $("#loading_num3").hide();
        $('.btn').prop('disabled', false);
        // alert(data.success);
        // window.location.href = data.success;
        // // window.open = data.success;
        // window.open(data.success, '_blank');
        // setTimeout(() => {
        //   // alert(data.success);
        //   alert("wait meanwhile we are redirecting you...");
          // window.location.href = redirect;
        // }, 3000);
        setTimeout(() => {
          // alert(data.success);
          alert("wait meanwhile we are redirecting you...");
          window.location.href = redirect;
        }, 3000);
      } else {
        $('.btn').prop('disabled', false);
        // alert("S");
        $("#loading_num3").hide();

        printErrorMsg(data.error);
      }
    }

  });
}

//
window.UpdateNumber = function(leadid, url) {
  $.ajax({
    type: 'POST',
    url: url,
    data: {
      leadid: leadid,
      // type: type,
      // reportName: reportName
    },
    success: function (data) {
      // alert(data);
      // $("#saledata").hide();
      // $("#saledata2").hide();
      $("#modalXample").html(data);
    }
  });
}


//
window.CallLogForm = function(id, form, url) {
  var rizwan = document.getElementById(form);
  // alert(id)
  // $("#btn_"+id).removeClass('btn-danger'); //versions newer than 1.6
  // $("#btn_"+id).AddClass('btn-danger'); //versions newer than 1.6
  // alert(id);
  $.ajax({
    type: "POST",
    url: url,
    data: new FormData(rizwan), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
    contentType: false, // The content type used when sending data to the server.
    cache: false, // To unable request pages to be cached
    processData: false,
    beforeSend: function () {
      // $("#request_login" + id).show();
      // // $(".request_call").hide();
      // $('#' + btn).prop('disabled', true);
    },
    success: function (msg) {
      //    alert(msg);
      if (msg == 1) {
        $("#btn_" + id).prop('value', 'Submitted'); //versions newer than 1.6
        $("#btn_" + id).prop('disabled', true); //versions newer than 1.6
      } else {
        alert("Something Wrong");
      }
      // var k = msg.split('###');
      // $("#dob").val(k[1]);
      // $("#expiry").val(k[2]);
      // $("#activation_emirate_expiry").val(k[2]);
      // var age = getAge(k[1]);
      // $("#age").val(age);
      // //  alert(age);

      // if (age < 21) {
      //     Swal.fire({
      //         icon: 'error',
      //         title: 'Age less than 21',
      //         text: 'User is not eligible for this package!',
      //         //  footer: '<a href>Why do I have this issue?</a>'
      //     })
      // }
    }
    // }
  });

}

$('#add_audio').click(function () {
            var numItems = $('.audio_action').length;

            // alert(numItems);

            // var salman_ahmed = $(".jackson_action").length();
            // var l = salman_ahmed.length;
            // alert(salman_ahmed);
            if (numItems < 8) {






                // get the last DIV which ID starts with ^= "klon"
                var $div = $('div[id^="klon_audio"]:last');

                // Read the Number from that DIV's ID (i.e: 3 from "klon3")
                // And increment that number by 1
                var num = parseInt($div.prop("id").match(/\d+/g), 10) + 1;

                // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
                var $klon = $div.clone(true).prop('id', 'klon_audio' + num);
                var jackson_action = $(".audio_action").html();
                var salmanahmed = 'salmanahmed';


                // Finally insert $klon wherever you want
                $div.after($klon.html(jackson_action));
            }

        });

        window.close_modal = function() {
          console.log("modal");

          // $(".close").click(function () {
          $("#call_back_at_elifee").val('');
          $("#call_back_new").val('');
          $("#call_back_mnp").val('');
          $("#later_date").val('');
          $("#reject_comment").val('');
          // })

        }

        window.imageIsLoaded = function(e) {
          $('#myImg').attr('src', e.target.result);
        };
        $(function () {
          $("#profile_pic").change(function () {
            if (this.files && this.files[0]) {
              var reader = new FileReader();
              reader.onload = imageIsLoaded;
              reader.readAsDataURL(this.files[0]);
            }
          });
        });


        window.imageIsLoaded1 = function(e) {
          $('#myImg1').attr('src', e.target.result);
        };
        $(function () {
          $("#cnic_front").change(function () {
            if (this.files && this.files[0]) {
              var reader = new FileReader();
              reader.onload = imageIsLoaded1;
              reader.readAsDataURL(this.files[0]);
            }
          });
        });


        window.imageIsLoaded2 = function(e) {
          $('#myImg2').attr('src', e.target.result);
        };
        $(function () {
          $("#cnic_backedi").change(function () {
            if (this.files && this.files[0]) {
              var reader = new FileReader();
              reader.onload = imageIsLoaded2;
              reader.readAsDataURL(this.files[0]);
            }
          });
        });
//
window.BookNum = function(id, url, Channel, number, redirect_url, e) {
  Swal.fire({
    title: 'Do you want to resever this number?',
    // showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: `Confirm`,
    // denyButtonText: `Don't save`,
  }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
      // Swal.fire('Saved!', '', 'success')
      $.ajax({
        type: 'POST',
        url: url,
        data: {
          id: id,
          Channel: Channel,
        },
        success: function (data) {
          // alert(data);
          // location.reload();
          if (data == 1) {
            // window.location.href = redirect_url;
            // alert(data.success);
            location.reload();
          } else if (data == 2) {
            alert("You already crossed Limit");
          } else {
            alert(data.success);
            // alert("Number Already Booked");
          }

          // $("#ReportingData").html(data);
        }
      });
    }
  });

}
//
window.ShowReserved = function(simtype, url, partner) {
  $.ajax({
    type: 'POST',
    url: url,
    data: {
      simtype: simtype,
      partner: partner,
    },
    success: function (data) {
      // alert(data);
      $("#broom").html(data);
    }
  });
}

window.ShowReservedCallCenter = function(url, agent_code, channel, status) {
  $.ajax({
    type: 'POST',
    url: url,
    data: {
      agent_code: agent_code,
      channel: channel,
      status: status,
    },
    beforeSend: function () {
      $("#loading_num3").show();
      // $("#loading_num3").html('<p> Loading </p>');
    },
    success: function (data) {
      // alert(data);
      $("#loading_num3").hide();
      $("#broom").html(data);
    }
  });
}
//
window.RevNum = function(id, url, cid, e) {

  Swal.fire({
    title: 'Do you want to Revive this number?',
    // showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: `Confirm`,
    // denyButtonText: `Don't save`,
  }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
      // Swal.fire('Saved!', '', 'success')
      $.ajax({
        type: 'POST',
        url: url,
        data: {
          id: id,
          cid: cid,
        },
        success: function (data) {
          // alert(data);
          location.reload();

          // $("#ReportingData").html(data);
        }
      });
    }
  });
}


window.HoldNum = function(id, url, cid, e) {

  Swal.fire({
    title: 'Do you want to Lead this number?',
    // showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: `Confirm`,
    // denyButtonText: `Don't save`,
  }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
      // Swal.fire('Saved!', '', 'success')
      $.ajax({
        type: 'POST',
        url: url,
        data: {
          id: id,
          cid: cid,
        },
        success: function (data) {
          // alert(data);
          location.reload();

          // $("#ReportingData").html(data);
        }
      });
    }
  });
}

//
window.ApprovedAccount = function(userid, url, status) {
  $.ajax({
    type: 'POST',
    url: url,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: {
      userid: userid,
      status: status,
    },
    success: function (data) {
      if (data == 1) {
        alert("Account Approved Succesfully");
        location.reload();
      } else if (data == 2) {
        alert("Account Reject Succesfully");
        location.reload();
      } else {
        alert("Something wrong");
      }
      // alert(data);
      // $("#saledata").hide();
      // $("#saledata2").hide();
      // $("#data").html(data);
    }
  });
}
//
window.RejectLeadVer = function(url, form, redirect) {
  var rizwan = document.getElementById(form);
  $.ajax({
    type: "POST",
    url: url,
    data: new FormData(rizwan), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
    contentType: false, // The content type used when sending data to the server.
    cache: false, // To unable request pages to be cached
    processData: false,
    beforeSend: function () {
      $("#loading_num3").show();
      // // $(".request_call").hide();
      $('.btn').prop('disabled', true);
      // $('#' + btn).prop('disabled', true);

    },
    success: function (data) {
      // alert(data);
      if ($.isEmptyObject(data.error)) {
        $("#loading_num3").hide();
        $('.btn').prop('disabled', false);
        // alert(data.success);
        // window.location.href = data.success;
        // // window.open = data.success;
        // window.open(data.success, '_blank');
        setTimeout(() => {
          // alert(data.success);
          alert("wait meanwhile we are redirecting you...");
          window.location.href = redirect;
        }, 1000);
      } else {
        $('.btn').prop('disabled', false);
        // alert("S");
        $("#loading_num3").hide();

        printErrorMsg(data.error);
      }
    }

  });
}


//
window.AssignJunaid = function(url, id, form) {
  // alert(form);


  var rizwan = document.getElementById(form);


  $.ajax({
    type: "POST",
    url: url,
    data: new FormData(rizwan), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
    contentType: false, // The content type used when sending data to the server.
    cache: false, // To unable request pages to be cached
    processData: false,
    beforeSend: function () {
      $("#loading_num2").show();
      // // $(".request_call").hide();
      // $('#' + btn).prop('disabled', true);
    },
    success: function (data) {
      if ($.isEmptyObject(data.error)) {
        $("#loading_num3").hide();
        $('.btn').prop('disabled', false);
        // alert(data.success);
        // window.location.href = data.success;
        // // window.open = data.success;
        window.open(data.success, '_blank');
        setTimeout(() => {
          // alert(data.success);
          alert("wait meanwhile we are redirecting you...");
          // window.location.href = redirect;
          window.location.href = "https://crm.connectcc.ae/";

        }, 4000);
      } else {
        // $('.btn').prop('disabled', false);
        alert("Pleae coordinate with IT TEAM =>" + data);
        // $("#loading_num3").hide();

        // printErrorMsg(data.error);
      }
      //    alert(msg);
      //  if (msg == 1) {
      //      Swal.fire(
      //          'Good job!',
      //          'You succesfully assigned lead!',
      //          'success'
      //      )
      //      setTimeout(() => {
      //         // window.location.href =
      //         window.location.href = "https://soft.riuman.com/home";
      //      }, 3000);
      //         // alert("Thank you for assign lead");
      //     }else{
      //         alert("Something wrong Kindly contact IT Team");
      //     }
      // $("#loading_num2").hide();
      // var k = msg.split('###');
      // $("#dob").val(k[1]);
      // $("#expiry").val(k[2]);
      // $("#activation_emirate_expiry").val(k[2]);
    }
    // }
  });
  // $.ajax({
  //     type: 'POST',
  //     url: url,
  //     data: {
  //         id: id,
  //         // partner:partner,
  //     },
  //     beforeSend: function () {
  //         $("#loading_num").show();
  //         // // $(".request_call").hide();
  //         // $('#' + btn).prop('disabled', true);
  //     },
  //     success: function (data) {
  //         // alert(data);
  //         if(data == 1){
  //             alert("Thank you for assign lead");
  //         }else{
  //             alert("Something wrong Kindly contact IT Team");
  //         }

  //         // $("#loading_num").hide();
  //         // $("#broom").html(data);
  //     }
  // });
}
//
//
window.test_reject = function(e) {
  // e.preventDefault();
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, Reject it!'
  }).then((result) => {
    if (result.isConfirmed) {
      // Swal.fire(
      //     'Deleted!',
      //     'Your lead has been rejected.',
      //     'success'
      // )
      $("#RejectMyLead")[0].submit();
    } else {
      e.preventDefault();
    }
  })
}

window.search_number_id = function(number) {
  // alert(form);
  var url = $("#number_search_url").val();
  // var number = $("#check").val();
  // var rizwan = document.getElementById(form);
  $.ajax({
    type: "POST",
    url: url,
    data: {
      number,
      number
    }, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
    // contentType: false, // The content type used when sending data to the server.
    // cache: false, // To unable request pages to be cached
    // processData: false,
    beforeSend: function () {
      // $("#request_login" + id).show();
      // // $(".request_call").hide();
      // $('#' + btn).prop('disabled', true);
      $("#loading_num").show();
    },
    success: function (msg) {
      //    alert(msg);
      $("#mylead").html(msg);
      // if (msg == 1) {
      //     $("#loading_num").hide();
      //     location.reload();
      // } else {
      //     alert("Something wrong");
      // }
      //  var k = msg.split('###');
      // // console.log(k[3] + ' ' + $k[4]);
      //  $("#name").val(k[1]);
      //  $("#CustomerNameAct").val(k[1]);
      //  $("#emirate_id").val(k[2]);
      //  $("#activation_emirate_expiry").val(k[2]);
      //  $("#application_date").val(k[3] + ' ' + k[4]);
    }
    // }
  });
  // }
  // }));
}

//
    $('#check').select2({
      placeholder: 'Please Search Numbers',
      // dropdownParent: $('#AddSkill'),
      // tags: true,
      minimumInputLength: 2,
      ajax: {
        url: '/search-number?id=' + $("#check").val(),
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: $.map(data, function (item) {
              return {
                text: item.number,
                id: item.number
              }
              // alert(item.number);
            })
          };
        }
      }
    });
    $('#checkMailNumber').select2({
      placeholder: 'Please Search Numbers',
      // dropdownParent: $('#AddSkill'),
      // tags: true,
      minimumInputLength: 2,
      ajax: {
        url: '/number-search-mail?id=' + $("#checkMailNumber").val(),
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: $.map(data, function (item) {
              return {
                text: item.number,
                id: item.number
              }
              // alert(item.number);
            })
          };
        }
      }
    });
    $("#check").on("change", function () {
      // console.log($(this).val());
      // console.log()
      // console.log()
      search_number_id($(this).val());
      // console.log($(this).val());
    });
    $("#checkMailNumber").on("change", function () {
      // console.log($(this).val());
      // console.log()
      // console.log()
      search_number_id($(this).val());
      // console.log($(this).val());
    });
    $('#checkleadno').select2({
      placeholder: 'Please Search Numbers',
      // dropdownParent: $('#AddSkill'),
      // tags: true,
      minimumInputLength: 2,
      ajax: {
        url: '/search-lead-number?id=' + $("#check").val(),
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: $.map(data, function (item) {
              return {
                text: item.number,
                id: item.number
              }
              // alert(item.number);
            })
          };
        }
      }
    });
    $("#checkleadno").on("change", function () {
      // console.log($(this).val());
      // console.log()
      // console.log()
      search_number_id($(this).val());
      // console.log($(this).val());
    });
    $('#check_customer_number').select2({
      placeholder: 'Please Search Numbers',
      // dropdownParent: $('#AddSkill'),
      // tags: true,
      minimumInputLength: 2,
      ajax: {
        url: '/search-customer-number?id=' + $("#check_customer_number").val(),
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: $.map(data, function (item) {
              return {
                text: item.number,
                id: item.number
              }
              // alert(item.number);
            })
          };
        }
      }
    });
    $("#check_customer_number").on("change", function () {
      // console.log($(this).val());
      // console.log()
      // console.log()
      search_number_id($(this).val());
      // console.log($(this).val());
    });
    $('.js-example-basic-single').select2({
      theme: 'bootstrap4',
    });
