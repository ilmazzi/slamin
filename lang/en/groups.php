<?php

return [
    // Main titles
    'title' => 'Groups',
    'my_groups' => 'My Groups',
    'all_groups' => 'All Groups',
    'create_group' => 'Create Group',
    'edit_group' => 'Edit Group',
    'group_details' => 'Group Details',
    'group_members' => 'Group Members',
    'group_invitations' => 'Group Invitations',
    'group_join_requests' => 'Join Requests',

    // Actions
    'create' => 'Create Group',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'join' => 'Join',
    'leave' => 'Leave',
    'invite' => 'Invite',
    'accept' => 'Accept',
    'decline' => 'Decline',
    'promote' => 'Promote',
    'demote' => 'Demote',
    'remove' => 'Remove',
    'send_request' => 'Send Request',
    'cancel_request' => 'Cancel Request',

    // Group fields
    'name' => 'Group Name',
    'description' => 'Description',
    'image' => 'Group Image',
    'visibility' => 'Visibility',
    'visibility_public' => 'Public',
    'visibility_private' => 'Private',
    'created_by' => 'Created by',
    'created_at' => 'Created at',
    'members_count' => 'Members Count',
    'role' => 'Role',
    'joined_at' => 'Joined at',

    // Roles
    'role_admin' => 'Administrator',
    'role_moderator' => 'Moderator',
    'role_member' => 'Member',

    // Messages
    'group_created' => 'Group created successfully!',
    'group_updated' => 'Group updated successfully!',
    'group_deleted' => 'Group deleted successfully!',
    'joined_group' => 'You have successfully joined the group!',
    'left_group' => 'You have left the group.',
    'member_removed' => 'Member removed from group.',
    'member_promoted' => 'Member promoted successfully.',
    'member_demoted' => 'Member demoted successfully.',
    'invitation_sent' => 'Invitation sent successfully!',
    'invitation_accepted' => 'Invitation accepted successfully!',
    'invitation_declined' => 'Invitation declined.',
    'request_sent' => 'Join request sent!',
    'request_accepted' => 'Join request accepted!',
    'request_declined' => 'Join request declined.',

    // Error messages
    'not_found' => 'Group not found.',
    'access_denied' => 'You do not have permission to access this group.',
    'already_member' => 'You are already a member of this group.',
    'not_member' => 'You are not a member of this group.',
    'invitation_exists' => 'You already have a pending invitation for this group.',
    'request_exists' => 'You already have a pending request for this group.',
    'cannot_leave_admin' => 'You cannot leave a group you are an administrator of. Promote another member first.',
    'cannot_remove_admin' => 'You cannot remove an administrator. Demote them first.',
    'cannot_promote_admin' => 'This user is already an administrator.',
    'cannot_demote_member' => 'This user is already a regular member.',

    // Filters and search
    'search_placeholder' => 'Search groups...',
    'filter_all' => 'All Groups',
    'filter_my_groups' => 'My Groups',
    'filter_public' => 'Public Groups',
    'filter_private' => 'Private Groups',
    'filter_admin' => 'Groups I Admin',

    // Form labels
    'group_name_placeholder' => 'Enter group name',
    'group_description_placeholder' => 'Describe your group...',
    'invitation_message_placeholder' => 'Invitation message (optional)',
    'join_request_message_placeholder' => 'Request message (optional)',

    // Statistics
    'stats' => 'Statistics',
    'total_members' => 'Total Members',
    'admins_count' => 'Administrators',
    'moderators_count' => 'Moderators',
    'members_count_label' => 'Members',
    'pending_invitations' => 'Pending Invitations',
    'pending_requests' => 'Pending Requests',

    // Group events
    'group_events' => 'Group Events',
    'no_group_events' => 'No events associated with this group.',
    'create_group_event' => 'Create Group Event',

    // Event permissions
    'event_permissions' => 'Event Permissions',
    'creator_only' => 'Creator Only',
    'group_admins' => 'Group Administrators',
    'group_members' => 'Group Members',

    // Pagination
    'showing' => 'Showing',
    'to' => 'to',
    'of' => 'of',
    'results' => 'results',

    // Quick actions
    'quick_actions' => 'Quick Actions',
    'view_members' => 'View Members',
    'manage_invitations' => 'Manage Invitations',
    'manage_requests' => 'Manage Requests',
    'group_settings' => 'Group Settings',

    // Confirmations
    'confirm_delete' => 'Are you sure you want to delete this group?',
    'confirm_leave' => 'Are you sure you want to leave this group?',
    'confirm_remove_member' => 'Are you sure you want to remove this member?',
    'confirm_decline_invitation' => 'Are you sure you want to decline this invitation?',
    'confirm_cancel_request' => 'Are you sure you want to cancel this request?',

    // Statuses
    'status_pending' => 'Pending',
    'status_accepted' => 'Accepted',
    'status_declined' => 'Declined',
    'status_expired' => 'Expired',

    // Additional information
    'group_info' => 'Group Information',
    'member_since' => 'Member since',
    'invited_by' => 'Invited by',
    'processed_by' => 'Processed by',
    'expires_at' => 'Expires at',
    'processed_at' => 'Processed at',

    // Empty states
    'no_groups' => 'No groups found.',
    'no_members' => 'No members in this group.',
    'no_invitations' => 'No pending invitations.',
    'no_requests' => 'No pending requests.',
    'no_my_groups' => 'You have not created any groups yet.',
    'no_joined_groups' => 'You are not a member of any groups yet.',

    // Tips
    'tips' => [
        'create_group' => 'Create a group to organize events and collaborate with other poets and organizers.',
        'invite_members' => 'Invite other users to grow your community.',
        'manage_permissions' => 'Manage permissions to maintain control of your group.',
        'group_events' => 'Associate events with the group for better organization.',
        'public_visibility' => 'Anyone can see and request to join the group.',
        'private_visibility' => 'Only invited users can join the group.',
    ],

    // Additional messages
    'delete_warning' => 'This action will permanently delete the group and all its data.',
    'delete_confirmation_text' => 'Are you sure you want to delete this group? This action cannot be undone.',
    'delete_confirmation_members' => 'All members will be removed from the group',
    'delete_confirmation_events' => 'Events associated with the group will be disassociated',
    'delete_confirmation_invitations' => 'All pending invitations and requests will be deleted',
    'invite_first_member' => 'Invite the first member to start building your community.',
    'you' => 'You',
]; 
