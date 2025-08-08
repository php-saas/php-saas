import { usePage } from '@inertiajs/react';
import { SharedData } from '@/types';
import { useEffect } from 'react';
import { toast } from 'sonner';
import { CheckCircle2Icon, CircleXIcon, InfoIcon, TriangleAlertIcon } from 'lucide-react';
import { Toaster } from '@/components/ui/sonner';

export default function Toast() {
  const page = usePage<SharedData>();

  useEffect(() => {
    if (page.props.flash && page.props.flash.success) {
      toast(
        <div className="flex items-center gap-2">
          <CheckCircle2Icon className="text-success size-5" />
          {page.props.flash.success}
        </div>,
      );
    }
    if (page.props.flash && page.props.flash.error) {
      toast(
        <div className="flex items-center gap-2">
          <CircleXIcon className="text-destructive size-5" />
          {page.props.flash.error}
        </div>,
      );
    }
    if (page.props.flash && page.props.flash.warning) {
      toast(
        <div className="flex items-center gap-2">
          <TriangleAlertIcon className="text-warning size-5" />
          {page.props.flash.warning}
        </div>,
      );
    }
    if (page.props.flash && page.props.flash.info) {
      toast(
        <div className="flex items-center gap-2">
          <InfoIcon className="text-info size-5" />
          {page.props.flash.info}
        </div>,
      );
    }
  }, [page.props.flash]);

  return <Toaster richColors position="bottom-center" />;
}
