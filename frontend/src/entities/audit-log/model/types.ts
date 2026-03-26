export interface AuditLogActor {
  id: number;
  display_name: string;
  email: string;
}

export interface AuditLogItem {
  id: number;
  entity_type: string;
  entity_id: number;
  action: string;
  old_status: string | null;
  new_status: string | null;
  comment: string | null;
  created_at: string | null;
  actor: AuditLogActor | null;
}
