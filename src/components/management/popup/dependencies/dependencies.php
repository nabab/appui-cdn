<bbn-splitter :collapsible="true"
              :resizableue="true"
              orientation="horizontal"
              class="dependencies"
>
  <bbn-pane>
    <bbn-table :source="source.depend"
               :order="[{field: 'name', dir: 'ASC'}]"
               :title-groups="[{
                              value: 'depend',
                              text: '<?=_("DEPEND")?>',
                            }]"
    >
      <bbns-column title="<?=_("Library")?>"
                   field="name"
                   group="depend"
      ></bbns-column>
      <bbns-column title="<?=_("Version")?>"
                   field="version"
                   group="depend"
                   class="bbn-c"
      ></bbns-column>
      <bbns-column v-if="source.listUpdate !== false"
                   title=" "
                   field="lib_name"
                   group="depend"
                   class="bbn-c"
                   :render="showIconUpdate"
      ></bbns-column>
      <bbns-column v-else
                   title=" "
                   field="update"
                   group="depend"
                   class="bbn-c"
                   :render="showIconUpdateOfManagment"
      ></bbns-column>
     </bbn-table>
  </bbn-pane>
  <bbn-pane>
    <bbn-table :source="source.dependent"
               :order="[{field: 'name', dir: 'ASC'}]"
               :title-groups="[{
                              value: 'dependent',
                              text: '<?=_("DEPENDENT")?>'
                            }]"
    >
      <bbns-column title="<?=_("Library")?>"
                   field="name"
                   group="dependent"
      ></bbns-column>
      <bbns-column title="<?=_("Version")?>"
                   field="version"
                   group="dependent"
                   class="bbn-c"
      ></bbns-column>
      <bbns-column v-if="source.listUpdate !== false"
                   title=" "
                   field="name"
                   group="dependent"
                   class="bbn-c"
                   :render="showIconUpdate"
      ></bbns-column>
    </bbn-table>
  </bbn-pane>
</bbn-splitter>
