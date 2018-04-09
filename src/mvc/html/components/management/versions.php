<div style="height: 200px; position:relative" v-if="versionsInfo.length">
  <bbn-table :source="versionsInfo"
             :editable="true"
             editor="appui-cdn-management-library_edit"
             :info="true"
             :sortable="true"
             ref="tableVersionsLib"
             class="bbn-full-screen"
  >
    <bbn-column title="<?=_('Version')?>"
                field="name"
                cls="bbn-c"
    ></bbn-column>
    <bbn-column title="<?=_('Date')?>"
                field="date_added"
                type="date"
                cls="bbn-c"
    ></bbn-column>
    <bbn-column :width="130"
                :tcomponent="$options.components.addVersions"
                :buttons="buttonsTable"
                cls="bbn-c"
    ></bbn-column>
  </bbn-table>
</div>
