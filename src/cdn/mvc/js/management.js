var
  // CDN tabstrip
  cdnTabStrip = $("#pe49ajAssj3knvVvn323").kendoTabStrip({
    animation:  {
      open: {
        effects: "fadeIn"
      }
    }
  }).data("kendoTabStrip"),

  // All libraries grid
  librariesGrid = $("#RRsj983Jfjnv2kasihj234").kendoGrid({
    dataSource: {
      transport: {
        read: function(o){
          if ( data.all_lib ){
            o.success(data.all_lib);
          }
        },
        create: function(o){
          var TVgetChecked = function(treeView){
            var checkedNodes = [],
                r = [];
            getChecked(treeView.dataSource.view(), checkedNodes);
            $.each(checkedNodes, function(i, a){
              r.push(a);
            });
            return JSON.stringify(r);
          };
          o.data.vname = $("#u93248safn328dasuq89yu").val();
          o.data.status = $("#as9hw3rhn9203nnfd9n23").val();
          o.data.files = TVgetChecked($("#ashd3538y1i35h8oasdj023").data("kendoTreeView"));
          o.data.languages = TVgetChecked($("#y7hhiawza3u9y983w2asj9h9xe4").data("kendoTreeView"));
          o.data.themes = TVgetChecked($("#y99hu8y4ss3a2s5423ld453wmn").data("kendoTreeView"));
          appui.f.post('cdn/management', o.data, function(d){
            
          });
        }
      },
      schema: {
        model :{
          id: "name",
          fields: {
            title: { type: "string" },
            name: { type: "string" },
            fname: { type: "string" },
            //latest: { type: "string"},
            website: { type: "string" },
            author: { type: "string" },
            licence: { type: "string" },
            download_link: { type: "string" },
            doc_link: { type: "string" },
            git: { type: "string" },
            support_link: { type: "string" },
            vname: { type: "string" },
            status: { type: "string" },
            files: { type: "string" },
            languages: { type: "string" },
            themes: { type: "string" }
          }
        }
      }
    },
    columns: [{
      title: 'Title',
      field: 'title'
    }, {
      title: 'Folder name',
      field: 'name',
    }, {
      title: 'Function name',
      field: 'fname'
    }, {
      title: 'Latest',
      field: 'latest'
    }, {
      title: 'WebSite',
      field: 'website',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.website && e.website.length ? '<a href="' + e.website + '" target="_blank"><i class="fa fa-globe"></i></a>' : '';
      }
    }, {
      title: 'Author',
      field: 'author',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.author && e.author.length ? '<i class="fa fa-user"></i>' : '';
      }
    }, {
      title: 'Licence',
      field: 'licence',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.licence && e.licence.length ? '<i class="fa fa-copyright"></i>' : '';
      },
      editor: function(container, options){
        var $input = $('<input name="' + options.field + '" style="width: 100%">');
        $input.appendTo(container).kendoDropDownList({
          dataSource: data.licences,
          dataTextField: "name",
          dataValueField: "licence",
          optionLabel: "Select one..."
        });
      }
    }, {
      title: 'Download',
      field: 'download_link',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.download_link && e.download_link.length ? '<a href="' + e.download_link + '" target="_blank"><i class="fa fa-download"></i></a>' : '';
      }
    }, {
      title: 'Doc.',
      field: 'doc_link',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.doc_link && e.doc_link.length ? '<a href="' + e.doc_link + '" target="_blank"><i class="fa' +
        ' fa-book"></i></a>' : '';
      }
    }, {
      title: 'GitHub',
      field: 'git',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.git && e.git.length ? '<a href="' + e.git + '" target="_blank"><i class="fa fa-github"></i></a>' : '';
      }
    }, {
      title: 'Supp.',
      field: 'support_link',
      width: 70,
      attributes:{
        style: "text-align: center"
      },
      template: function(e){
        return e.support_link && e.support_link.length ? '<a href="' + e.support_link + '" target="_blank"><i class="fa' +
        ' fa-ambulance"></i></a>' : '';
      }
    }, {
      width: 80,
      template: function(e){
        return '<a class="k-button k-button-icontext k-grid-edit" href="javascript:;"><i class="fa fa-edit"></i></a>' +
          '<a class="k-button k-button-icontext k-grid-delete" href="javascript:;"><i class="fa fa-trash"></i></a>';
      }
    }, {
      hidden: true,
      title: '',
      command: [{
        name: "edit",
        text: {
          edit: "Mod.",
          update: "Save",
          cancel: "Cancel"
        }
      }, {
        name: "destroy",
        test: "Del."
      }]
    }],
    toolbar: function(){
      return '<div class="toolbar">' +
                // Add library button
                '<div style="width: 50%; display: inline-block">' +
                  '<a class="k-button k-button-icontext k-grid-add" href="javascript:;">' +
                    '<i class="fa fa-plus" style="margin-right: 5px"></i>Add library' +
                  '</a>' +
                '</div>' +
                // Search library
                '<div style="width: 50%; display: inline-block; text-align: right">' +
                  '<i class="fa fa-search" style="margin-right: 5px"></i>' +
                  '<input id="F4LLL9jdn3nhasS38sh301dfs">' +
                '</div>' +
              '</div>';
    },
    editable: {
      mode: "popup",
      window: {
        width: 850
      }
    },
    edit: function(e){
      appui.f.log(e);
      var cont = e.container,
          kcont = cont.data("kendoWindow");

      // Set title
      kcont.title(
        e.model[e.model.name] ? "Edit Library" : "New Library"
      );

      // Fix inputs width
      $("input", cont).width("90%");

      // Set required inputs
      $("input[name=title], input[name=name]", cont).attr("required", "required");

      // Remove duplicated buttons
      $("div.k-edit-field:last, div.k-edit-label:last", cont).remove();

      // Remove "latest" input
      $("input[name=latest]").parent().prev().remove();
      $("input[name=latest]").parent().remove();

      // Insert mode
      if ( !e.model[e.model.name] ){
        // Version's content DataSource
        var filesDS = new kendo.data.HierarchicalDataSource({
              data: []
            }),
            languagesDS = new kendo.data.HierarchicalDataSource({
              data: []
            }),
            themesDS = new kendo.data.HierarchicalDataSource({
              data: []
            });
        // Add version form
        $("div.k-edit-form-container", cont).prepend($("#932f9u4923rjasdu09j3333").html());
        // Hide "Save" button
        $("a.k-button.k-grid-update", "div.k-edit-buttons", cont).hide();
        // Insert "Next" button
        $("div.k-edit-buttons", cont).append(
          $('<a href="#" class="k-button k-button-icontext b-next">' +
            'Next' +
            '<span class="k-icon k-i-arrow-e" style="margin-left: 3px; margin-right: -3px"></span>' +
          '</a>').click(function(){
            if ( $("input[name=name]", cont).val().length ){
              appui.f.post('cdn/versions', {
                folder: $("input[name=name]", cont).val()
              }, function(d){
                if ( d.data.version && d.data.tree ){
                  // Change window title
                  kcont.title("New Library's Version");
                  // Hide library form
                  $("div.k-edit-field:visible, div.k-edit-label:visible", cont).hide();
                  // Hide next button
                  $("a.k-button.b-next", "div.k-edit-buttons", cont).hide();
                  // Show version form
                  $("#asdahf8923489yhf98923hr").show();
                  // Set version's name
                  $("#u93248safn328dasuq89yu").val(d.data.version);
                  // Version's status dropdownlist
                  $("#as9hw3rhn9203nnfd9n23").kendoDropDownList({
                    dataSource: ['Stable', 'Development'],
                    optionLabel: "Select one..."
                  });
                  // Content panelbar
                  $("#0ash834fh9qqqwhf8h34h9").kendoPanelBar({
                    expandMode: "single"
                  });
                  // Create files treeviews
                  $("#ashd3538y1i35h8oasdj023").kendoTreeView({
                    dataSource: filesDS,
                    checkboxes: {
                      checkChildren: true
                    }
                  }).data("kendoTreeView").dataSource.data(d.data.tree);
                  // Create files treeviews
                  $("#y7hhiawza3u9y983w2asj9h9xe4").kendoTreeView({
                    dataSource: languagesDS,
                    checkboxes: {
                      checkChildren: true
                    }
                  }).data("kendoTreeView").dataSource.data(d.data.tree);
                  // Create files treeviews
                  $("#y99hu8y4ss3a2s5423ld453wmn").kendoTreeView({
                    dataSource: themesDS,
                    checkboxes: {
                      checkChildren: true
                    }
                  }).data("kendoTreeView").dataSource.data(d.data.tree);
                  // Show save button
                  $("a.k-button.k-grid-update", "div.k-edit-buttons", cont).show();
                  // Add before button
                  $("div.k-edit-buttons", cont).prepend(
                    $('<a href="#" class="k-button k-button-icontext b-before">' +
                      '<span class="k-icon k-i-arrow-w"></span>' +
                      'Before' +
                      '</a>').click(function(){
                      // Change window title
                      kcont.title("New Library");
                      // Show library form inputs
                      $("div.k-edit-field:hidden, div.k-edit-label:hidden", cont).show();
                      // Hide version form
                      $("#asdahf8923489yhf98923hr").hide();
                      // Hide save and before buttons
                      $("a.k-button.k-grid-update, a.k-button.b-before", "div.k-edit-buttons", cont).hide();
                      // Show next button
                      $("a.k-button.b-next", "div.k-edit-buttons", cont).show();
                    })
                  );
                }
              });
            }
            else {
              alert('Set the folder name first, please.');
              $("input[name=name]", cont).focus();
            }
          })
        );
      }
    },
    // Library's versions subgrid
    detailInit: function(d){
      $("<div/>").appendTo(d.detailCell).kendoGrid({
        dataSource: {
          transport: {
            read: function(e){
              appui.f.post('cdn/versions', {id_lib: d.data.name}, function(p){
                if ( p.data ){
                  e.success(p.data);
                }
              });
            }
          }
        },
        columns: [{
          title: 'Version',
          field: 'name'
        }, {
          title: 'Status',
          field: 'status'
        }, {
          title: 'Date',
          field: 'date_added'
        }],
        selectable: true,
        change: function(e){
          var grid = this,
              dataItem = grid.dataItem(this.select());

          appui.f.alert($("#kkk3jaSdh23490hqAsdha93").html(), 'Library: ' + d.data.title + ' - Version: ' + dataItem.name, 1000, 800, function(w){
            // Details tabstrip
           $("#888Hdfh3iaJAsi12KAsdh32lJa").kendoTabStrip({
              animation:  {
                open: {
                  effects: "fadeIn"
                }
              }
            });

            // Library tab
            // Load library's info template
            $("#nnnDfgsj939uqkok2912en99asd").html($("#sdg39t3023j9fass8y34t8y8asf3y89").html());
            // Set the library's info inputs to readonly
            $("#nnnDfgsj939uqkok2912en99asd").find("input").each(function(i,v){
              $(v).attr("readonly", "readonly");
            });
            // Bind library's info
            kendo.bind($("#nnnDfgsj939uqkok2912en99asd"), d.data);
            // Library's versions grid
            $("#hufsa93hias9n38fn3293h389r2").kendoGrid({
              dataSource: grid.dataSource.data(),
              columns: [{
                title: 'Name',
                field: 'name'
              }, {
                title: 'Status',
                field: 'status'
              }, {
                title: 'Date',
                field: 'date_added'
              }]
            });

            // Version's tab
            // Bind version's info
            kendo.bind($("#i435osojdi2oajsdioasdi22jiod"), dataItem);
            // Version's files TreeView
            $("#daij444jasdjhi332jiosdajo").html(dataItem.files_tree);
            // Version's dependencies list
            $("#isfih3huasdf92huf823hyhas93").html(function(){
              if ( dataItem.dependencies.length ){
                var ret = '<ul>';
                $.each(dataItem.dependencies, function(i,v){
                  ret += '<li>' + v.title + ' - ' + v.version + '</li>';
                });
                ret += '</ul>';
                return ret;
              }
              return '<div style="text-align: center">NO DEPENDENCIES</div>';
            });

            // TabStrip height fix
            $("div.k-tabstrip-wrapper", w).addClass("appui-full-height");
            $(w).redraw();

          });
        }
      });
    }
  }),

  // Function for get the treeview checked elements
  getChecked = function(nodes, checkedNodes) {
    for (var i = 0; i < nodes.length; i++) {
      if (nodes[i].checked && nodes[i].path) {
        checkedNodes.push(nodes[i].path);
      }

      if (nodes[i].hasChildren) {
        getChecked(nodes[i].children.view(), checkedNodes);
      }
    }
  };

// Main TabStrip container padding fix
$("#pe49ajAssj3knvVvn323").closest("div.k-content").css("padding", 0);

// Search field
$("#F4LLL9jdn3nhasS38sh301dfs").kendoAutoComplete({
  placeholder: 'Search library...'
});

appui.f.log(data);