<template>
  <div class="max-w-xl container mx-auto mt-8">
	  <search :portal="false"/>
    <table>

		<thead>
			<tr>
        <th class="bg-green-lighter" colspan="4" v-html="code"></th>
      </tr>
      <tr>
				<th>Current quarter</th>
				<th>Previous Quarter</th>
				<th>Previous Year</th>
				<th>Earning per share</th>
			</tr>
		</thead>
      <tbody>
        <tr>
          <td>
            <input autofocus="true" type="number" min="0" :value="(report || {}).current_quarter" @keyup="update('current_quarter', $event.target.value)">
          </td>
          <td>
            <input type="number" min="0" :value="(report || {}).previous_quarter" @keyup="update('previous_quarter', $event.target.value)">
          </td>
          <td>
            <input type="number" min="0" :value="(report || {}).previous_year" @keyup="update('previous_year', $event.target.value)">
          </td>
          <td>
            <input type="number" min="0" :value="(report || {}).earning_per_share" @keyup="update('earning_per_share', $event.target.value)">
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
import MouseTrap from 'mousetrap';
import axios from 'axios';
export default {
  name: "report",
  props: ["code","report"],
  methods:{
	  update(type,value){
		  axios.put('/report/'+this.code,{type,value});
	  }
  },
  data() {
    return {
      years: ["75-76"]
    };
  }
};
</script>