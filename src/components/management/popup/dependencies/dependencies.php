<bbn-splitter :collapsible="true"
              :resizableue="true"
              orientation="horizontal"
              class="dependencies bbn-overlay"
>
  <bbn-pane>
    <bbn-table :source="source.depend"
               :order="[{field: 'name', dir: 'ASC'}]"
               :title-groups="[{
                              value: 'depend',
                              text: '<?= _("Dependencies") ?>',
                            }]"
    >
      <bbns-column title="<?= _("Library") ?>"
                   field="name"
                   group="depend"
      ></bbns-column>
      <bbns-column title="<?= _("Version") ?>"
                   field="version"
                   group="depend"
                   class="bbn-c"
      ></bbns-column>
      <bbns-column title=" "
                   field="lib_name"
                   group="depend"
                   class="bbn-c"
                   :render="showIconUpdate"
      ></bbns-column>
     </bbn-table>
  </bbn-pane>
  <bbn-pane>
    <bbn-table :source="source.dependent"
               :order="[{field: 'name', dir: 'ASC'}]"
               :title-groups="[{
                              value: 'dependent',
                              text: '<?= _("Slaves") ?>'
                            }]"
    >
      <bbns-column title="<?= _("Library") ?>"
                   field="name"
                   group="dependent"
      ></bbns-column>
      <bbns-column title="<?= _("Version") ?>"
                   field="version"
                   group="dependent"
                   class="bbn-c"
      ></bbns-column>
      <bbns-column title=" "
                   field="name"
                   group="dependent"
                   class="bbn-c"
                   :render="showIconUpdate"
      ></bbns-column>
    </bbn-table>
  </bbn-pane>
</bbn-splitter>
