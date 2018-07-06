<div class="table-responsive">
    <table class="table mb-0 text-left">
        <thead>
            <tr>
                <th style="min-width: 200px">Name</th>
                <th class="d-none d-sm-block">Description</th>
                <th>Farms</th>
                <th>Crops</th>
                <th>Harvested</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
            <tr>
                <th><a href="{{ url(route('groups.show', ['group' => $group->slug])) }}">{{ $group->name }}</a></th>
                <td class="d-none d-sm-block">{{ $group->description }}</td>
                <td>{{ $group->players_count }}</td>
                <td>{{ $group->accessBalance() }}</td>
                <td>{{ number_format($group->rewards()->notDry()->sum('player_reward.total')) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>