<td class="js-edit-row--trigger pointer">{{ $projectcode->description }}</td>
<td>{{ $projectcode->code }}</td>
<td>{{ $projectcode->allocation_type_frequency }}</td>
<td>{{ $projectcode->purpose }}</td>
<td>{{ $projectcode->pre_approval_required }}</td>
<td>{{ $projectcode->action_item }}</td>
<td>{{ $projectcode->workday_code }}</td>
<td>{{ $projectcode->workday_description }}</td>
<td>{{ eFirstLast($projectcode->authorizer_person_id) }}</td>
<td>{{ eFirstLast($projectcode->fiscal_person_id) }}</td>
