/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 11:11
 */

// Function to add/remove files to/from version's files reorder list
var addDelFilesOrder = function(item, forceAdd){
  var cont = $("#asdahf8923489yhf98923hr:visible"),
    kcont = cont.closest(".k-content:visible").data("kendoWindow");

  if ( item.length ){
    $.each(item, function(i, v){
      addDelFilesOrder(v, forceAdd);
    });
  }
  else {
    if ( item.items !== undefined ){
      addDelFilesOrder(item.items);
    }
    else {
      if ( item.checked || forceAdd ){
        $("#joisfd8723hifwe78238hds", cont).append(
          '<div path="' + item.path + '" class="k-alt" style="margin-bottom: 5px;">' +
            '<i class="nf nf-fa-arrows" style="margin-right: 5px"></i>' + item.path +
          '</div>'
        );
      }
      else {
        $("div[path='" + item.path + "']", "#joisfd8723hifwe78238hds", cont).remove();
      }
    }
  }
};
