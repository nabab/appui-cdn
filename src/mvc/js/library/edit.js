/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 29/12/2016
 * Time: 13:35
 */
var libraryEdit = function(e){
  var cont = e.container,
      kcont = cont.data("kendoWindow");

  $("#kjasdy723ri89asdhah8oasdaso892", cont).show();
  // Set the old library's name to hidden input
  $("input[name=old_name]", cont).val(e.model.name);
  // Licence DropDownList
  $("div#kjasdy723ri89asdhah8oasdaso892 select[name=licence]", cont).kendoDropDownList({
    dataSource: data.licences,
    dataTextField:  "name",
    dataValueField: "licence",
    optionLabel: data.lng.SelectOne
  });
  // Import from GitHub button
  $("a.k-button.fa-download", cont).click(function(){
    bbn.fn.post("cdn/github/info", {
      url: e.model.git,
      only_info: true
    }, function(d){
      if ( d.data ){
        for (var prop in d.data){
          if ( (prop !== 'name') && (prop !== 'latest') && (e.model[prop] !== undefined) ){
            if ( prop === 'licence' ){
              var lic = bbn.fn.get_field(data.licences, 'name', d.data[prop], 'licence');
              if ( !lic ){
                lic = bbn.fn.get_field(data.licences, 'licence', d.data[prop], 'licence');
              }
              if ( lic ){
                e.model.set(prop, lic);
              }
            }
            else {
              e.model.set(prop, d.data[prop]);
            }
          }
        }
      }
    })
  });
};
