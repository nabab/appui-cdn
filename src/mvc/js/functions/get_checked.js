/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 11:09
 */

// Function for get the treeview checked elements
var TVgetChecked = function(treeView){
  var checkedNodes = [],
      getChecked = function(nodes, checkedNodes){
        var getItems = function(nodes){
          for ( var i = 0; i < nodes.length; i++ ){
            if ( (nodes[i].items !== undefined) && nodes[i].items.length ){
              getItems(nodes[i].items);
            }
            else if ( nodes[i].path ){
              checkedNodes.push(nodes[i].path);
            }
          }
        };

        for ( var i = 0; i < nodes.length; i++ ){
          if ( nodes[i].checked && nodes[i].path && !nodes[i].hasChildren ){
            checkedNodes.push(nodes[i].path);
          }
          else if ( nodes[i].checked && nodes[i].hasChildren ){
            getItems(nodes[i].children.options.data.items);
          }
          else if ( !nodes[i].checked && nodes[i].hasChildren ){
            getChecked(nodes[i].children.view(), checkedNodes);
          }
        }
      };

  getChecked(treeView.dataSource.view(), checkedNodes);

  return JSON.stringify(checkedNodes);
};