import { Badge } from '@/components/ui/badge';

export default function ProjectRole({ role }: { role: string }) {
  const variant = () => {
    switch (role) {
      case 'owner':
        return 'success';
      case 'admin':
        return 'warning';
      case 'viewer':
        return 'outline';
      default:
        return 'outline';
    }
  };
  return <Badge variant={variant()}>{role}</Badge>;
}
