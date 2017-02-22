var configurationsGrid = $("#98324nas9t4pash9d2n3ifau"),
    configurationsGridInit = function(){
      configurationsGrid.kendoGrid({
        dataSource: {
          transport: {
            read: function(o){
              if ( data.all_conf ){
                o.success(data.all_conf);
              }
            },
            create: function(o){
              bbn.fn.post('cdn/configurations', o.data, function(d){
                if ( d.data && d.data.length ){
                  o.success(d.data);
                }
                else {
                  o.error();
                }
              });
            },
            update: function(o){
              bbn.fn.post('cdn/configurations', o.data, function(d){
                if ( d.data ){
                  o.success(d.data);
                }
                else {
                  o.error();
                }
              });
            },
            destroy: function(o){
              if ( o.data.hash !== undefined ){
                bbn.fn.post('cdn/configurations', {hash: o.data.hash}, function(d){
                  if ( d.data.success ){
                    o.success();
                  }
                  else{
                    o.error();
                  }
                });
              }
            }
          },
          schema: {
            model :{
              id: "hash",
              fields: {
                hash: { type: "string" },
                config: { type: "string" },
                cached: { type: "string" }
              }
            }
          }
        },
        columns: [{
          title: data.lng.hash,
          field: "hash"
        }, {
          title: data.lng.configuration,
          field: "config",
          template: function(e){
            if ( e.config.length ){
              var conf = JSON.parse(e.config),
                ret = '';
              $.each(conf, function(i,v){
                ret += 'Library: <strong>' + v.lib_title + '</strong> - Version: <strong>' + v.ver_name + ( v.ver_id === 'latest' ? ' (latest)</strong><br>' : '</strong><br>');
              });
              return ret;
            }
            return '';
          }
        }, {
          title:  data.lng.cached,
          field: "cached"
        }, {
          title: '',
          width: 80,
          command: [{
            name: "edit",
            text: {
              edit: data.lng.mod,
              update: data.lng.save,
              cancel: data.lng.cancel,
            },
            template: '<a class="k-button k-grid-edit fa fa-edit" href="javascript:;"></a>'
          }, {
            name: data.lng.destroy,
            test: "Del.",
            template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
          }]
        }],
        toolbar: function(){
          return '<div class="toolbar">' +
            // Search configuration
            '<div style="width: 50%; display: inline-block">' +
            '<i class="fa fa-search" style="margin: 0 5px"></i>' +
            '<input id="h3294lasd9j234oasd9u" style="width: 300px">' +
            '</div>' +
            // Add configuration button
            '<div style="width: 50%; display: inline-block; text-align: right"">' +
            '<a class="k-button k-button-icontext k-grid-add" href="javascript:;">' +
            '<i class="fa fa-plus" style="margin-right: 5px"></i>Add configuration' +
            '</a>' +
            '</div>' +
            '</div>';
        },
        editable: {
          mode: "popup",
          confirmation: data.lng.delete_this_entry,
          window: {
            width: 850
          }
        },
        edit: function(e){
          var cont = e.container,
            kcont = cont.data("kendoWindow");

          // Set title
          kcont.title(
            e.model.hash ? "Edit Configuration" : "New Configuration"
          );

        }
      });
      // Search field
      $("#h3294lasd9j234oasd9u").kendoAutoComplete({
        placeholder: data.lng.search_conf + '...',
      });
    };

