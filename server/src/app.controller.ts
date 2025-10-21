import { Controller, Get } from '@nestjs/common';

@Controller()
export class AppController {
  @Get()
  getHello(): string {
    // サーバーが正常に動作しているか確認するためのルート
    return 'NestJS server is running! PostgreSQL connection status is handled by TypeORM.';
  }
}