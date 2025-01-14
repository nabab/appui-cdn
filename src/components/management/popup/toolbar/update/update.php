<bbn-table :source="source.list">
  <bbns-column label= "<?= _('Library') ?>"
              field= "title"
  ></bbns-column>
  <bbns-column label= "<?= _('Local version') ?>"
              field= "local"
              cls= "bbn-c"
  ></bbns-column>
  <bbns-column label= "<?= _('Latest version') ?>"
              field= "latest"
              cls= "bbn-c bbn-bg-red bbn-white"
  ></bbns-column>
  <bbns-column label= " "
               width= 140
               field= "title"
               :buttons= "buttons"
               cls= "bbn-c"
  ></bbns-column>
</bbn-table>
