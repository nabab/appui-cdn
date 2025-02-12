<div style="height: 200px; position:relative" v-if="versionsInfo.length">
  <bbn-table :source="versionsInfo"
             :editable="true"
             editor="appui-cdn-management-library_edit"
             :info="true"
             :sortable="true"
             ref="tableVersionsLib"
             class="bbn-overlay"
  >
    <bbns-column label="<?= _('Version') ?>"
                field="name"
                cls="bbn-c"
    ></bbns-column>
    <bbns-column label="<?= _('Date') ?>"
                field="date_added"
                type="date"
                cls="bbn-c"
    ></bbns-column>
    <bbns-column :width="220"
                :tcomponent="$options.components.addVersions"
                :buttons="buttonsTable"
                cls="bbn-c"
    ></bbns-column>
  </bbn-table>
</div>
